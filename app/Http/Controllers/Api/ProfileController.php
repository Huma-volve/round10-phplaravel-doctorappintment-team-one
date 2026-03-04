<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // GET /api/v1/profile
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }

    // PATCH /api/v1/profile
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['sometimes','string','max:255'],
            'phone' => ['sometimes','string','max:20','unique:users,phone,'.$user->id],
            'birthdate' => ['sometimes','nullable','date'],
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => new UserResource($user)
        ]);
    }

    // POST /api/v1/profile/photo
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required','image','max:2048']
        ]);

        $user = $request->user();

        $path = $request->file('photo')->store('profile_photos','public');

        $user->photo_url = Storage::url($path);
        $user->save();

        return response()->json([
            'message' => 'Photo updated successfully',
            'photo_url' => $user->photo_url
        ]);
    }

    // PATCH /api/v1/profile/password
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
}