<?php

namespace App\Http\Controllers\Web\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $doctor = $user->doctor()->with('specialties')->first();

        return view('doctor.profile', compact('user', 'doctor'));
    }

    public function changePassword(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password incorrect'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password updated successfully');
    }

    public function updateSpecialties(Request $request)
    {
        $doctor = auth()->user()->doctor;

        $request->validate([
            'specialties' => 'required|array|min:1',
            'specialties.*' => 'exists:specialties,id',
        ]);

        $doctor->specialties()->sync($request->specialties);

        return redirect()->route('doctor.profile')
            ->with('success', 'Specialties updated successfully');
    }


    public function updateProfile(Request $request)
    {
        $doctor = auth()->user()->doctor;

        $request->validate([
            'bio' => 'required|string|min:10',
            'years_of_experience' => 'required|integer|min:0',
        ]);

        $doctor->update([
            'bio' => $request->bio,
            'years_of_experience' => $request->years_of_experience,
        ]);

        return redirect()->route('doctor.profile')
            ->with('success', 'Profile updated successfully');
    }
}
