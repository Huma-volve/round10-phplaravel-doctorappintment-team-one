<?php

namespace Database\Factories;

use App\Models\doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 * <\App\Models\Model>
 */
class ClincFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doctor_id' => Doctor::factory()->approved(),
            'name' => fake()->company() . ' Clinic',
            'address' => fake()->address(),
            'lat' => fake()->latitude(22, 31),   
            'lng' => fake()->longitude(25, 37),  
            'session_duration_minutes' => fake()->randomElement([15, 20, 30, 45, 60]),
            'session_price_cents' => fake()->numberBetween(5000, 100000), 
            'currency' => 'EGP',
        ];
    }
}
