<?php

namespace Database\Factories;

use App\Models\doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 * <\App\Models\Model>
 */
class CancellationPolicyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $feeType = fake()->randomElement(['none', 'fixed', 'percent']);

        return [
            'doctor_id' => Doctor::factory()->approved(),
            'allowed_cancel' => fake()->boolean(80),
            'allowed_cancel_before_minutes' => fake()->randomElement([60, 120, 240, 1440, 2880]),
            'fee_type' => $feeType,
            'fee_value' => $feeType === 'none' ? 0 : fake()->numberBetween(5, 50),
            'allow_reschedule' => fake()->boolean(70),
            'allowed_reschedule_before_minutes' => fake()->randomElement([60, 120, 240, 1440]),
        ];
    }
}
