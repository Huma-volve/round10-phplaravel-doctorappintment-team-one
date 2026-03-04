<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorProfileController extends Controller
{
    /**
     * GET /api/v1/doctor/profile
     * Get logged-in doctor's profile
     */
    public function show(Request $request)
    {
        $user = $request->user();

        // Ensure the user is a doctor
        if ($user->role !== 'doctor') {
            return response()->json([
                'message' => 'Only doctors can access this profile'
            ], 403);
        }

        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            return response()->json([
                'message' => 'Doctor profile not found'
            ], 404);
        }

        return new DoctorResource($doctor);
    }

    /**
     * PATCH /api/v1/doctor/profile
     * Update doctor profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'doctor') {
            return response()->json([
                'message' => 'Only doctors can update this profile'
            ], 403);
        }

        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            return response()->json([
                'message' => 'Doctor profile not found'
            ], 404);
        }

        $validated = $request->validate([
            'bio' => ['sometimes', 'nullable', 'string'],
            'years_of_experience' => ['sometimes', 'integer', 'min:0', 'max:80'],
            'license_number' => ['sometimes', 'string', 'max:80'],
        ]);

        // Prevent editing license after verification
        if (
            $doctor->verification_status === 'approved' &&
            array_key_exists('license_number', $validated)
        ) {
            return response()->json([
                'message' => 'License number cannot be changed after approval'
            ], 422);
        }

        $doctor->update($validated);

        return response()->json([
            'message' => 'Doctor profile updated successfully',
            'data' => new DoctorResource($doctor)
        ]);
    }
}