<?php

namespace App\Http\Controllers\Web\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdatePatientRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

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

    public function update(UpdatePatientRequest $request, User $patient){
        $data = $request->validated();

        if($data['photo_url'] instanceof UploadedFile){
            $data['photo_url'] = $data['photo_url']->store('patients', 'public');
        }
    
        $patient->update($data);

        return redirect()->route('doctor.patients.index')->with('success', 'Patient updated successfully');
    }

    public function destroy(User $patient){
        $patient->delete();

        return redirect()->route('doctor.patients.index')->with('success', 'Patient deleted successfully');
    }
}
