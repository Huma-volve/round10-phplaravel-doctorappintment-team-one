<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\MeNotificationsController;
use App\Http\Controllers\Api\NotificationPreferencesController;
use App\Http\Controllers\Api\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReviewsController;
use App\Models\Reviews;
use Illuminate\Http\JsonResponse;


Route::middleware('auth:sanctum')->group(function () {

    // notification logs

});


Route::get('/doctors/nearby', [DoctorController::class, 'getNearbyDoctors']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('reviews/getAll', [ReviewsController::class , 'getReview']);
Route::apiResource('reviews', ReviewsController::class);

