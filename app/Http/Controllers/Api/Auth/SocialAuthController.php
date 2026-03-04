<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle(){
        $url = Socialite::driver('google')
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return response()->json([
            'url' => $url
        ], 200);
    }
    public function handleCallback()
    {
        try {

            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('social_id', $googleUser->id)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'social_id' => $googleUser->id,
                    'social_type' => 'google',
                    'password' => Hash::make(Str::random(24)),
                    'role' => 'patient',
                    'status'=>'active'
                ]);
            }

            $token = $user->createToken($user->name.'google-login')->plainTextToken;

            return response()->json([
                'message' => 'Successfully logged in',
                'user' => $user,
                'token' => $token,
            ],200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
