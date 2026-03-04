<?php


use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\SocialAuthController;
use App\Http\Controllers\Api\bookingcontroller;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReviewsController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Notification\NotificationController;
use App\Models\Reviews;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    // Profile endpoints
    Route::get('/profile', [ProfileController::class,'show']);
    Route::patch('/profile', [ProfileController::class,'update']);
    Route::post('/profile/photo', [ProfileController::class,'uploadPhoto']);
    Route::patch('/profile/password', [ProfileController::class,'updatePassword']);
    Route::delete('/profile/account', [ProfileController::class,'deleteAccount']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markRead']);
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead']);
    Route::get('/notification-preferences', [NotificationController::class, 'preferences']);
    Route::patch('/notification-preferences', [NotificationController::class, 'updatePreference']);


    // Bookings
    Route::post('/appointments/book', [bookingcontroller::class, 'bookslot']);
    Route::get('/appointments/my', [bookingcontroller::class, 'myBookings']);
    Route::delete('/mybooking/{id}/cancel', [bookingcontroller::class,'cancel']);
    Route::put('/booking/{id}/update', [bookingcontroller::class,'update']);
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

Route::get('v1/doctors/nearby', [DoctorController::class, 'getNearbyDoctors']);


Route::get('reviews/getAll', [ReviewsController::class , 'getReview']);
Route::apiResource('reviews', ReviewsController::class);

Route::post('/payments/create-intent', [PaymentController::class, 'createPaymentIntent']);
Route::post('/payments/webhook', [PaymentController::class, 'webhook']);

// Public user endpoint
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Doctor bookings (public)
Route::get('doctorBookings',[bookingcontroller::class,'doctorBookings']);

// Social Auth
Route::get('/auth/google/redirect',[SocialAuthController::class,'redirectToGoogle']);
Route::get('/auth/google/callback',[SocialAuthController::class,'handleCallback']);

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

Route::middleware('auth:sanctum')->group(function () {
    route::get('/search-history',[SearchController::class,'index']);
    route::post('/search' , [SearchController::class , 'search_for_doctor']);
});
