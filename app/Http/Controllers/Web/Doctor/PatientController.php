<?php

namespace App\Http\Controllers\Web\Doctor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request){
        $doctor = auth()->user()->doctor;

        $patients = User::whereHas('bookings', function($query) use($doctor){
            $query->where('doctor_id', $doctor->id);
        })->paginate(15);

        return view('doctor.patients.index', compact('patients'));
    }

    public function show(User $patient){
        $patient->load([
            'medicalRecords', 
            'bookings' => function($query){
                $query->where('doctor_id', auth()->user()->doctor->id);
            }
        ]);

        return view('doctor.patients.show', compact('patient'));
    }

    public function edit(User $patient){
        return view('doctor.patients.edit', compact('patient'));
    }
}
