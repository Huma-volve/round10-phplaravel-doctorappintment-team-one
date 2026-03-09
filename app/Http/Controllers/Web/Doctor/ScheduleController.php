<?php

namespace App\Http\Controllers\Web\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $doctorId = auth()->user()->doctor->id;

        $clinics = Clinic::with('timeSlots.bookings')
            ->where('doctor_id', $doctorId)
            ->get();

//        return view('doctor.schedule', compact('clinics'));
        return $clinics;
    }

}
