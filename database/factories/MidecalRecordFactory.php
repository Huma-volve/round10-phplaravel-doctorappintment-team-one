<?php

namespace Database\Factories;

use App\Models\bookings;
use App\Models\clinics;
use App\Models\doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 * <\App\Models\Model>
 */
class MidecalRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $followUpRequired = fake()->boolean(40);

        return [
            'patient_id' => User::factory()->patient(),
            'doctor_id' => Doctor::factory()->approved(),
            'clinic_id' => fake()->optional(0.8)->passthrough(Clinics::factory()),
            'appointment_id' => fake()->optional(0.7)->passthrough(Bookings::factory()->completed()),
            'diagnosis' => fake()->randomElement([
                'Hypertension',
                'Type 2 Diabetes',
                'Upper Respiratory Infection',
                'Migraine',
                'Anxiety Disorder',
                'Acute Pharyngitis',
                'Vitamin D Deficiency',
                'Lower Back Pain',
            ]),
            'notes' => fake()->paragraph(),
            'recommendations' => fake()->sentences(3, true),
            'follow_up_required' => $followUpRequired,
            'follow_up_after_days' => $followUpRequired ? fake()->randomElement([7, 14, 30, 60, 90]) : null,
        ];
    }
}
