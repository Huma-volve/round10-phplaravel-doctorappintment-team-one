<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->doctor(),
            'specialties' => fake()->randomElement([
                'Cardiology', 'Dermatology', 'Neurology', 'Orthopedics',
                'Pediatrics', 'Psychiatry', 'Radiology', 'Surgery', 'Urology',
            ]),
            'license_number' => strtoupper(fake()->bothify('LIC-####-??##')),
            'bio' => fake()->paragraph(3),
            'years_of_experience' => fake()->numberBetween(1, 10),
            'verification_status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'verification_notes'  => fake()->optional(0.5)->sentence(),
        ];
    }
    public function approved(): static
    {
        return $this->state([ 'verification_status' => 'approved', 'verification_notes'  => null]);
    }

    public function pending(): static
    {
        return $this->state(['verification_status' => 'pending']);
    }
}
