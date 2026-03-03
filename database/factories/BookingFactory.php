<?php

namespace Database\Factories;

use App\Models\doctor;
use App\Models\doctor_time_slots;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 * <\App\Models\Model>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('-30 days', '+30 days');
        $endsAt = (clone $startsAt)->modify('+30 minutes');

        return [
            'patient_id' => User::factory()->patient(),
            'doctor_id' => Doctor::factory()->approved(),
            'time_slot_id' => Doctor_time_slots::factory()->booked(),
            'starts_at_utc' => $startsAt,
            'ends_at_utc' => $endsAt,
            'status' => fake()->randomElement([
                'draft',
                'pending_payment',
                'confirmed',
                'completed',
                'cancelled_by_patient',
                'cancelled_by_doctor',
                'rescheduled',
            ]),
            'payment_method' => fake()->randomElement(['stripe', 'paypal', 'cash']),
            'payment_status' => fake()->randomElement(['unpaid', 'paid', 'failed', 'refunded', 'partially_refunded']),
            'amount_cents' => fake()->numberBetween(5000, 100000),
            'currency' => 'EGP',
        ];
    }

    public function completed(): static
    {
        return $this->state([
            'status' => 'completed',
            'payment_status' => 'paid',
        ]);
    }

    public function confirmed(): static
    {
        return $this->state([
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);
    }
}
