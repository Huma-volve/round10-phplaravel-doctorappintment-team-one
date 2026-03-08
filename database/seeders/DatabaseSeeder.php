<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\DoctorTimeSlot;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {

            $specialties = collect([
                'Cardiology',
                'Dermatology',
                'Orthopedics',
                'Pediatrics',
                'Neurology',
            ])->map(function ($name) {
                return Specialty::create(['name' => $name]);
            });


            for ($i = 1; $i <= 20; $i++) {
                User::create([
                    'name' => "Patient $i",
                    'email' => "patient$i@example.com",
                    'phone' => '011000000' . $i,
                    'password' => bcrypt('password'),
                    'role' => 'patient',
                    'status' => 'active',
                ]);
            }


            for ($i = 1; $i <= 15; $i++) {

                $user = User::create([
                    'name' => "Doctor $i",
                    'email' => "doctor$i@example.com",
                    'phone' => '010000000' . $i,
                    'password' => Hash::make('password'),
                    'role' => 'doctor',
                    'status' => 'active',
                ]);

                $doctor = Doctor::create([
                    'user_id' => $user->id,
                    'license_number' => 'LIC-' . strtoupper(Str::random(6)),
                    'bio' => 'Experienced doctor in multiple specialties.',
                    'years_of_experience' => rand(3, 20),
                    'verification_status' => 'approved',
                ]);

                $doctor->specialties()->attach(
                    $specialties->random(rand(1, 3))->pluck('id')->toArray()
                );

                // for ($c = 1; $c <= rand(1, 2); $c++) {

                //     Clinic::create([
                //         'doctor_id' => $doctor->id,
                //         'name' => "Clinic $c - Dr $i",
                //         'address' => 'Cairo, Egypt',
                //         'lat' => 30.0444 + (rand(-100, 100) / 1000),
                //         'lng' => 31.2357 + (rand(-100, 100) / 1000),
                //         'session_duration_minutes' => 30,
                //         'session_price_cents' => rand(20000, 80000),
                //         'currency' => 'EGP',
                //     ]);
                // }
            }
        });

        // Call the DoctorTimeSlotSeeder
        $this->call(DoctorTimeSlotSeeder::class);
    }

}
