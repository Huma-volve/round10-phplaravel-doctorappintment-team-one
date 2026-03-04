<?php

namespace App\Http\Enums;

enum DoctorVerificationStatus: string{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

}