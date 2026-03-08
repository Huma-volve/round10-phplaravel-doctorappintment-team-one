<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\ReviewsController;
use App\Http\Controllers\Api\SearchController;

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\SocialAuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Notification\NotificationController;

// -----------------------------
// Auth Routes
// -----------------------------
Route::prefix('auth')->group(function () {
    Route::post('/login',[AuthController::class, 'login']);
    Route::post('/register',[AuthController::class, 'requestOtpForRegister']);
    Route::post('/forgot-password',[AuthController::class, 'forgotPassword']);
    Route::post('/reset-password',[AuthController::class, 'resetPassword']);
    Route::post('/verify-otp',[AuthController::class, 'verifyOtp']);

    Route::get('/google/redirect',[SocialAuthController::class,'redirectToGoogle']);
    Route::get('/google/callback',[SocialAuthController::class,'handleCallback']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// -----------------------------
// Authenticated API Routes (v1)
// -----------------------------
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class,'show']);
    Route::patch('/profile', [ProfileController::class,'update']);
    Route::post('/profile/photo', [ProfileController::class,'uploadPhoto']);
    Route::patch('/profile/password', [ProfileController::class,'updatePassword']);
    Route::delete('/profile/account', [ProfileController::class,'deleteAccount']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markRead']);
    Route::get('/notification-preferences', [NotificationController::class, 'preferences']);
    Route::patch('/notification-preferences', [NotificationController::class, 'updatePreference']);

    // Bookings
    Route::post('/appointments/book', [BookingController::class, 'bookslot']);
    Route::get('/appointments/my', [BookingController::class, 'myBookings']);
    Route::delete('/mybooking/{id}/cancel', [BookingController::class,'cancel']);
    Route::put('/booking/{id}/update', [BookingController::class,'update']);

    // Messaging
    Route::post('/messages', [MessageController::class, 'store']);
    Route::post('/messages/reply/{conversation_id}', [MessageController::class, 'reply']);
    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
    Route::post('/conversations/{conversation}/read', [ConversationController::class, 'markAsRead']);
    Route::get('/conversations/{conversation}/unread', [ConversationController::class, 'unreadCount']);
    Route::post('/messages/media', [MessageController::class,'sendMediaMessage']);
    Route::post('/conversations/{conversation_id}/favorite', [ConversationController::class, 'toggleFavorite']);
    Route::post('/conversations/{conversation_id}/archive', [ConversationController::class, 'archiveConversation']);

    // Search history
    Route::get('/search-history',[SearchController::class,'index']);
    Route::post('/search', [SearchController::class , 'search_for_doctor']);
});

// -----------------------------
// Public v1 Routes
// -----------------------------
Route::prefix('v1')->name('v1.')->group(function () {

    // Doctors
    Route::prefix('/doctors')->name('doctors.')->group(function () {
        Route::get('/nearby', [DoctorController::class, 'getNearbyDoctors'])->name('nearby');
        Route::get('/{doctor}', [DoctorController::class, 'getDoctor'])->name('show');
    });

    // User favorites
    Route::prefix('/user/favorites')->name('user.favorites.')->group(function () {
        Route::prefix('/doctors')->name('doctors.')->group(function () {
            Route::get('/', [FavoriteController::class, 'listFavorites'])->name('list');
            Route::post('/add', [FavoriteController::class, 'addToFavorite'])->name('add');
            Route::post('/remove', [FavoriteController::class, 'removeFromFavorite'])->name('remove');
        });
    });

    // Reviews
    Route::get('reviews/getAll', [ReviewsController::class , 'getReview']);
    Route::apiResource('reviews', ReviewsController::class);

    // Doctor bookings public
    Route::get('doctorBookings',[BookingController::class,'doctorBookings']);
});

// -----------------------------
// Payments
// -----------------------------
Route::post('/payments/create-intent', [PaymentController::class, 'createPaymentIntent']);
Route::post('/payments/webhook', [PaymentController::class, 'webhook']);
// Public user endpoint
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
