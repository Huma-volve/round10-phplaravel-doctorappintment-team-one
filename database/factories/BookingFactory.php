<?php

namespace Database\Factories;

use App\Models\doctor;
use App\Models\doctor_time_slots;
use App\Models\DoctorTimeSlot;
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
        $doctorUser = Doctor::factory()->approved()->create();
        $patientUser = User::factory()->patient()->create();
        $timeSlot = DoctorTimeSlot::factory()->booked()->create();

        return [
            'patient_id' => $patientUser->id,
            'doctor_id' => $doctorUser->id,
            'time_slot_id' => $timeSlot->id,
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
