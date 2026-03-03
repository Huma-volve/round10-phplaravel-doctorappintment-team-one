<?php

namespace Database\Factories;

use App\Models\conversations;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['text', 'image', 'video']);

        return [
            'conversation_id' => Conversations::factory(),
            'sender_user_id' => User::factory(),
            'type' => $type,
            'body' => fake()->sentence(),
            'media_url' => fake()->imageUrl(),
            'media_size_bytes' => fake()->numberBetween(1000, 10000),
            'media_mime' => $type === 'image' ? 'image/jpeg' : ($type === 'video' ? 'video/mp4' : null),
            'sent_at_utc' => fake()->dateTimeBetween('-7 days', 'now'),
            'read_at_utc' => fake()->optional(0.7)->dateTimeBetween('-7 days', 'now'),
        ];
    }
}
