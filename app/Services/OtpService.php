<?php

namespace App\Services;

use App\Models\OtpCode;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    public function createOtp($user, $purpose = 'login')
    {
        $code = rand(100000, 999999);

        OtpCode::create([
            'user_id' => $user->id,
            'channel' => 'email',
            'destination' => $user->email,
            'purpose' => $purpose,
            'code_hash' => Hash::make($code),
            'expires_at_utc' => now()->addMinutes(5),
            'attempts' => 0,
            'max_attempts' => 5,
            'send_count' => 1,
        ]);

        return $code;
    }

    public function verifyOtp($user, $code, $purpose = 'login')
    {
        $otp = OtpCode::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->first();

        if (!$otp) return false;

        if ($otp->expires_at_utc < now()) return false;

        if ($otp->attempts >= $otp->max_attempts) return false;

        if (!Hash::check($code, $otp->code_hash)) {
            $otp->increment('attempts');
            return false;
        }

        $otp->delete();

        return true;
    }
}
