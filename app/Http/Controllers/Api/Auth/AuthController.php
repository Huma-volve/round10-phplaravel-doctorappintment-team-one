<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserLoginRequest;
use App\Http\Requests\Api\UserRegisterRequest;
use App\Http\Requests\Api\UserVerifyOtp;
use App\Models\User;
use App\Services\MailService;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // login
    public function login(UserLoginRequest $request)
    {

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('password-login')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token
        ],200);
    }

    private function sendOtpToUser(User $user,OtpService $otpService,MailService $mailService,$purpose)
    {
        $code = $otpService->createOtp($user, $purpose);

        $mailService->sendOtp($user->email, $code);

        return response()->json([
            'message' => 'OTP sent successfully , please check your email'
        ]);
    }
    public function forgotPassword(Request $request , OtpService $otpService, MailService $mailService){
        $request->validate([
            'email' => 'required|string|email|exists:users,email',
        ]);

        $user = User::where('email',$request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Invalid credentials'],400);
        }

        return $this->sendOtpToUser($user,$otpService,$mailService,'reset_password');
    }

    public function resetPassword(Request $request, OtpService $otpService)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|confirmed|min:8'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        if (!$otpService->verifyOtp($user, $request->otp, 'reset_password')) {
            return response()->json([
                'message' => 'Invalid or expired OTP'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
            'status' => 'active'
        ]);

        return response()->json([
            'message' => 'Password reset successfully',
        ]);
    }
    public function requestOtpForRegister(UserRegisterRequest $request, OtpService $otpService, MailService $mailService)
    {
        $user = User::Create($request->validated());
        return $this->sendOtpToUser($user,$otpService,$mailService,'verify_email');
    }

    public function verifyOtp(UserVerifyOtp $request, OtpService $otpService)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!$otpService->verifyOtp($user, $request->otp, $request->purpose)) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        $user->status = 'active';
        $user->email_verified_at = now();
        $user->save();
        $token = $user->createToken('otp-login')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
//         $request->user()->tokens()->delete(); logged out from all devices
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
