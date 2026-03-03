<?php

namespace Database\Factories;

use App\Models\User;
use Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OtpCodeFactory extends Factory
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
            'channel' => 'email',
            'destination' => fake()->safeEmail(),
            'purpose' => fake()->randomElement([
                'login',
                'verify_phone',
                'verify_email',
                'reset_password',
            ]),
            'code_hash' => Hash::make((string) fake()->numberBetween(100000, 999999)),
            'expires_at_utc' => now()->addMinutes(10),
            'attempts' => 0,
            'max_attempts' => 5,
            'send_count' => 1,
            'last_sent_at_utc' => now(),
            'consumed_at_utc' => null,
        ];
    }
}
