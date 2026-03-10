<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CreateDoctorRequest;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    public function create()
    {
        $specialties = \App\Models\Specialty::all();

        return view('admin.doctors.create', compact('specialties'));
    }

    public function store(CreateDoctorRequest $request)
    {
//        $password = Str::random(8);
//        TODO send email
        // we can send email with password to doctor but to be known for team member
        $password = '12345678';
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'password'=>Hash::make($password),
            'role'=>'doctor',
            'status'=>'active'
        ]);

       $doctor =  Doctor::create([
            'user_id'=>$user->id,
            'bio'=>$request->bio,
            'years_of_experience'=>$request->years_of_experience,
            'license_number'=>$request->license_number,
            'verification_status'=>'approved'
        ]);

        $doctor->specialties()->attach($request->specialties);

        return redirect()->route('admin.doctors.create')
            ->with('success','Doctor created successfully');
    }


}
