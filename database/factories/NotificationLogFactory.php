<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class NotificationLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement([
                'booking_confirmed',
                'booking_cancelled',
                'payment_received',
                'review_received',
                'otp_sent',
                'reminder',
            ]),
            'channel' => fake()->randomElement(['email', 'in_app']),
            'title' => fake()->sentence(5),
            'body' => fake()->paragraph(),
            'data' => json_encode(['booking_id' => fake()->numberBetween(1, 100)]),
            'provider_message_id' => fake()->optional()->bothify('msg_??########'),
            'sent_at_utc' => now(),
            'read_at_utc' => fake()->optional(0.6)->dateTimeBetween('-7 days', 'now'),
        ];
    }
}
