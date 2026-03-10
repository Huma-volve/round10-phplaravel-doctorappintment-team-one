<?php

namespace App\Http\Controllers\Web\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(){
        $doctor = auth()->user()->doctor;
        
        $clinics = $doctor->clinics;

        return view('doctor.settings.edit', compact('doctor','clinics'));
    }
}
