<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Conversation;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        $patient = User::firstOrCreate(
            ['email' => 'patient@test.com'],
            [
                'name' => 'Test Patient',
                'phone' => '0500000001',
                'password' => Hash::make('123456'),
                'role' => 'patient',
                'status' => 'active',
            ]
        );

        $doctorUser = User::firstOrCreate(
            ['email' => 'doctor@test.com'],
            [
                'name' => 'Test Doctor',
                'phone' => '0500000002',
                'password' => Hash::make('123456'),
                'role' => 'doctor',
                'status' => 'active',
            ]
        );

        Doctor::firstOrCreate(
            ['user_id' => $doctorUser->id],
            [
                'license_number' => 'LIC-' . $doctorUser->id,
                'bio' => 'Test doctor bio for chat feature.',
                'years_of_experience' => 5,
                // verification_status عنده default pending، لكن ممكن تحطه لو تحب:
                // 'verification_status' => 'approved',
            ]
        );



        // Conversation
        Conversation::firstOrCreate(
            [
                'patient_id' => $patient->id,
                'doctor_id'  => $doctorUser->id,
            ],
            [
                'last_message_at_utc' => now(),
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
