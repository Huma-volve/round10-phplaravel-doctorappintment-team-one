<?php

namespace App\Services;

use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendOtp($email ,$otp){
        Mail::to($email)->send(new OtpMail($otp));
        return response()->json(['message' => 'OTP send successfully, please check your email'],200);
    }
}
