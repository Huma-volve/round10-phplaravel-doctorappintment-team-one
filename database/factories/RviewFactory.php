<?php

namespace Database\Factories;

use App\Models\bookings;
use App\Models\doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 * <\App\Models\Model>
 */
class RviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id' => Bookings::factory()->completed(),
            'patient_id' => User::factory()->patient(),
            'doctor_id'  => Doctor::factory()->approved(),
            'rating'     => fake()->numberBetween(1, 5),
            'comment'    => fake()->optional(0.8)->paragraph(),
        ];
    }
}
