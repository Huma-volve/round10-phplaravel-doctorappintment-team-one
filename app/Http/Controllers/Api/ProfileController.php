<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    
    public function show(Request $request)
    {
        $user = $request->user()->load([
            'bookings' => fn($q) => $q->orderBy('created_at', 'desc'),
            'reviews',
            'favorites',
            'doctor' => fn($q) => $q->with(['specialties', 'clinics', 'reviews', 'bookings'])
        ]);
        
        return new UserResource($user);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['sometimes','string','max:255'],
            'email' => ['sometimes','email','unique:users,email,'.$user->id],
            'phone' => ['sometimes','string','max:20','unique:users,phone,'.$user->id],
            'birthdate' => ['sometimes','nullable','date'],
        ]);

        $user->update($validated);
        
        $user->load([
            'bookings' => fn($q) => $q->orderBy('created_at', 'desc'),
            'reviews',
            'favorites',
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user)
        ]);
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required','image','max:2048']
        ]);

        $user = $request->user();

        $path = $request->file('photo')->store('profile_photos','public');

        $user->photo_url = Storage::url($path);
        $user->save();
        
        
        $user->load([
            'bookings' => fn($q) => $q->orderBy('created_at', 'desc'),
            'reviews',
            'favorites',
            'doctor' => fn($q) => $q->with(['specialties', 'clinics', 'reviews', 'bookings'])
        ]);

        return response()->json([
            'message' => 'Photo updated successfully',
            'data' => new UserResource($user)
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required','min:8','confirmed']
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Current password incorrect'
            ],422);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully'
        ]);
    }


    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'password' => ['required', 'string']
        ]);

        if (!Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Password is incorrect. Account deletion cancelled.'
            ], 422);
        }

        
        if ($user->photo_url) {
            try {
               
                $path = str_replace('/storage/', '', $user->photo_url);
                Storage::disk('public')->delete($path);
            } catch (\Exception $e) {
              
            }
        }

        
        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'message' => 'Account deleted successfully. All your data has been removed.'
        ], 200);
    }
}