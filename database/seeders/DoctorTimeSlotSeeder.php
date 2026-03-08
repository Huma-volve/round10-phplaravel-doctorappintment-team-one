<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\DoctorTimeSlot;
use Illuminate\Database\Seeder;

class DoctorTimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all doctors
        $doctors = Doctor::all();

        foreach ($doctors as $doctor) {
            // Create one clinic per doctor if none exists
            $clinic = Clinic::firstOrCreate(
                ['doctor_id' => $doctor->id],
                [
                    'name' => "Clinic - Dr {$doctor->user->name}",
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'address' => '123 Medical Street, Cairo, Egypt',
                    'lat' => 30.0444 + (rand(-100, 100) / 1000),
                    'lng' => 31.2357 + (rand(-100, 100) / 1000),
                    'session_duration_minutes' => 30,
                    'session_price_cents' => rand(20000, 80000),
                    'currency' => 'USD',
                ]
            );

            // Create time slots for next 30 days
            $baseDate = now()->startOfDay()->addDays(1); // Start from tomorrow

            for ($day = 0; $day < 30; $day++) {
                $date = $baseDate->copy()->addDays($day);

                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }

                // Create 4 time slots per day (9:00, 10:00, 14:00, 15:00)
                $times = [
                    ['start' => 9, 'end' => 9.5],   // 9:00 - 9:30
                    ['start' => 10, 'end' => 10.5], // 10:00 - 10:30
                    ['start' => 14, 'end' => 14.5], // 14:00 - 14:30
                    ['start' => 15, 'end' => 15.5], // 15:00 - 15:30
                ];

                foreach ($times as $time) {
                    $startsAt = $date->copy()->setTime((int)$time['start'], (($time['start'] % 1) * 60));
                    $endsAt = $date->copy()->setTime((int)$time['end'], (($time['end'] % 1) * 60));

                    DoctorTimeSlot::create([
                        'doctor_id' => $doctor->id,
                        'clinic_id' => $clinic->id,
                        'starts_at_utc' => $startsAt,
                        'ends_at_utc' => $endsAt,
                        'status' => 'available',
                        'capacity' => 1,
                    ]);
                }
            }
        }

        echo "✅ Doctor time slots seeded successfully!";
    }
}
