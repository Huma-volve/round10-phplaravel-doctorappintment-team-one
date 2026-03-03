<?php

namespace Database\Factories;

use App\Models\clinics;
use App\Models\doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 * <\App\Models\Model>
 */
class DoctorTimeSlotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $startsAt = fake()->dateTimeBetween('now', '+30 days');
        $endsAt = (clone $startsAt)->modify('+30 minutes');

        return [
            'doctor_id' => Doctor::factory()->approved(),
            'clinic_id' => Clinics::factory(),
            'starts_at_utc' => $startsAt,
            'ends_at_utc' => $endsAt,
            'status' => fake()->randomElement(['available', 'booked']),
            'capacity' => 1,
        ];
    }

    public function available(): static
    {
        return $this->state(['status' => 'available']);
    }

    public function booked(): static
    {
        return $this->state(['status' => 'booked']);
    }
}
