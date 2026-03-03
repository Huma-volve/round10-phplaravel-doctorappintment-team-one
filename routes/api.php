<?php

use App\Http\Controllers\Api\DoctorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/doctors/nearby', [DoctorController::class, 'getNearbyDoctors']);
