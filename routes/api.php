<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\SocialAuthController;
use App\Http\Controllers\Api\bookingcontroller;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReviewsController;
use App\Http\Controllers\Notification\NotificationController;
use App\Models\Reviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\JsonResponse;


use App\Http\Controllers\Api\FavoriteController;

Route::middleware('auth:sanctum')->group(function () {

    // Notification logs (history)
    Route::get('/v1/notifications', [NotificationController::class, 'index']);
    Route::patch('/v1/notifications/{id}/read', [NotificationController::class, 'markRead']);
    Route::patch('/v1/notifications/read-all', [NotificationController::class, 'markAllRead']);

});

Route::prefix('v1')->name('v1.')->group(function () {


    Route::prefix('/doctors')->name('doctors.')->group(function () {
        Route::get('/nearby', [DoctorController::class, 'getNearbyDoctors'])->name('nearby');
        Route::get('/{doctor}', [DoctorController::class, 'getDoctor'])->name('show');
    });

    Route::prefix('/user/favorites')->name('user.favorites.')->group(function () {

        Route::prefix('/doctors')->name('doctors.')->group(function () {
            Route::get('/', [FavoriteController::class, 'listFavorites'])->name('list');
            Route::post('/add', [FavoriteController::class, 'addToFavorite'])->name('add');
            Route::post('/remove', [FavoriteController::class, 'removeFromFavorite'])->name('remove');
        });


    });

});




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('reviews/getAll', [ReviewsController::class , 'getReview']);
Route::apiResource('reviews', ReviewsController::class);

Route::post('/payments/create-intent', [PaymentController::class, 'createPaymentIntent']);
Route::post('/payments/webhook', [PaymentController::class, 'webhook']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::apiResource('booking',bookingcontroller::class);
// Route::get('/doctors/{doctor_id}/slots', [bookingcontroller::class, 'availableSlots']);
Route::post('/appointments/book', [bookingcontroller::class, 'bookslot']);
    //  ->middleware('auth:sanctum');
    Route::get('/appointments/my', [bookingcontroller::class, 'myBookings']);
Route::delete('/mybooking/{id}/cancel/',[bookingcontroller::class,'cancel']);
Route::put('booking/{id}/update',[bookingcontroller::class,'update']);
Route::get('doctorBookings',[bookingcontroller::class,'doctorBookings']);


Route::prefix('auth')->group(function () {
    Route::post('/login',[AuthController::class, 'login']);
    Route::post('/register',[AuthController::class, 'requestOtpForRegister']);

    Route::post('/forgot-password',[AuthController::class, 'forgotPassword']);
    Route::post('/reset-password',[AuthController::class, 'resetPassword']);


    Route::post('/verify-otp',[AuthController::class, 'verifyOtp']);

    Route::get('/google/redirect',[SocialAuthController::class,'redirectToGoogle']);
    Route::get('/google/callback',[SocialAuthController::class,'handleCallback']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout',   [AuthController::class, 'logout']);
    });
});
