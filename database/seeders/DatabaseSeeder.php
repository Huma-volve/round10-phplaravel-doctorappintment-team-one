<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\AuditLog::factory()->count(15)->create();
        \App\Models\Bookings::factory()->count(50)->create();
        \App\Models\Conversation_favorites::factory()->count(5)->create();
        \App\Models\Clinics::factory()->count(20)->create();
        \App\Models\Conversation_favorites::factory()->count(10)->create();
        \App\Models\Conversations::factory()->count(50)->create();
        \App\Models\Doctor_time_slots::factory()->count(15)->create();
        \App\Models\Doctor::factory()->count(25)->create();
        \App\Models\Favorites::factory()->count(10)->create();
        \App\Models\Medical_records::factory()->count(20)->create();
        \App\Models\Medical_records::factory()->count(50)->create();
        \App\Models\Notification_logs::factory()->count(50)->create();
        \App\Models\Otp_codes::factory()->count(50)->create();
        \App\Models\Payments::factory()->count(50)->create();
        \App\Models\Reviews::factory()->count(50)->create();
        \App\Models\Search_histories::factory()->count(50)->create();
        \App\Models\Specialties::factory()->count(50)->create();



        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
