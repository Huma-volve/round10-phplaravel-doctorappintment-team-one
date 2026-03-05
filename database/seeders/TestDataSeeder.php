<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // ✅ 1. Patient User
        $patientUserId = DB::table('users')->insertGetId([
            'name'       => 'Test Patient',
            'email'      => 'patien979@test.com',
            'phone'      => '01111111111',
            'password'   => Hash::make('password123'),
            'role'       => 'patient',
            'status'     => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ✅ 2. Doctor User
        $doctorUserId = DB::table('users')->insertGetId([
            'name'       => 'Test Doctor',
            'email'      => 'doctor889@test.com',
            'phone'      => '02222222222',
            'password'   => Hash::make('password123'),
            'role'       => 'doctor',
            'status'     => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ✅ 3. Doctor
        $doctorId = DB::table('doctors')->insertGetId([
            'user_id'             => $doctorUserId,
            'license_number'      => 'LIC998859',
            'bio'                 => 'Test Doctor Bio',
            'years_of_experience' => 5,
            'verification_status' => 'approved',
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
// ✅ 4. Clinic
$clinicId = DB::table('clinics')->insertGetId([
    'doctor_id'                => $doctorId,
    'name'                     => 'Test Clinic',
    'address'                  => 'Test Address',
    'lat'                      => 30.0444,
    'lng'                      => 31.2357,
    'session_duration_minutes' => 30,
    'session_price_cents'      => 50000,
    'currency'                 => 'usd',
    'created_at'               => now(),
    'updated_at'               => now(),
]);

// ✅ 5. Slot
$slotId = DB::table('doctor_time_slots')->insertGetId([
    'doctor_id'     => $doctorId,
    'clinic_id'     => $clinicId,
    'status'        => 'available',
    'capacity'      => 1,
    'starts_at_utc' => '2026-04-01 10:00:00',
    'ends_at_utc'   => '2026-04-01 10:30:00',
    'created_at'    => now(),
    'updated_at'    => now(),
]);
      

        // ✅ 5. Booking
        DB::table('bookings')->insert([
            'patient_id'     => $patientUserId,
            'doctor_id'      => $doctorId,
            'time_slot_id'   => $slotId,
            'starts_at_utc'  => '2026-04-01 10:00:00',
            'ends_at_utc'    => '2026-04-01 10:30:00',
            'status'         => 'pending_payment',
            'payment_method' => 'stripe',
            'payment_status' => 'unpaid',
            'amount_cents'   => 50000,
            'currency'       => 'usd',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
}