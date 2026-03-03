<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuditLog>
 */
class AuditLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'actor_user_id' => $user->id,
            'action' => fake()->randomElement([
                'login',
                'logout',
                'booking.create',
                'booking.cancel',
                'profile.update',
                'doctor.approve',
                'payment.process',
            ]),
            'target_type' => fake()->randomElement(['Booking', 'User', 'Doctor', 'Payment']),
            'target_id' => fake()->numberBetween(1, 100),
            'ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'meta' => json_encode(['reason' => fake()->sentence()]),
            'created_at_utc' => now(),
        ];
    }
}
