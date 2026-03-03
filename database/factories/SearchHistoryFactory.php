<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 * <\App\Models\Model>
 */
class SearchHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => User::factory()->patient(),
            'query' => fake()->words(3, true),
            'specialty' => fake()->randomElement([
                'Cardiology',
                'Dermatology',
                'Neurology',
                'Pediatrics',
                'Surgery',
            ]),
            'lat' => fake()->latitude(22, 31),
            'lng' => fake()->longitude(25, 37),
            'doctor_name' => fake()->optional(0.5)->name(),
        ];
    }
}
