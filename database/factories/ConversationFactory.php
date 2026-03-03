<?php

namespace Database\Factories;

use App\Models\doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 * <\App\Models\Model>
 */
class ConversationFactory extends Factory
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
            'doctor_id' => Doctor::factory()->approved(),
            'last_message_at_utc' => fake()->dateTimeBetween('-30 days', 'now'),
            'patient_archived' => false,
            'doctor_archived' => false,
        ];
    }
}
