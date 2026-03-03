<?php

namespace Database\Factories;

use App\Models\bookings;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 * <\App\Models\Model>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $provider = fake()->randomElement(['stripe', 'paypal', 'cash']);

        return [
            'booking_id' => Bookings::factory()->confirmed(),
            'provider' => $provider,
            'provider_payment_id' => strtoupper(fake()->bothify('pay_??########')),
            'provider_customer_id' => strtoupper(fake()->bothify('cus_??########')),
            'status' => fake()->randomElement([
                'initiated',
                'requires_action',
                'succeeded',
                'failed',
                'refunded',
                'partially_refunded',
            ]),
            'amount_cents' => fake()->numberBetween(500, 10000),
            'refunded_cents' => 0,
            'currency' => 'EGP',
            'meta' => json_encode([
                'gateway' => $provider,
                'ip' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
            ]),
        ];
    }

    public function succeeded(): static
    {
        return $this->state(['status' => 'succeeded', 'refunded_cents' => 0]);
    }
}
