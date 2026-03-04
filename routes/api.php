<?php
use App\Http\Controllers\Notification\NotificationController;


use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\MeNotificationsController;
use App\Http\Controllers\Api\NotificationPreferencesController;
use App\Http\Controllers\Api\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReviewsController;
use App\Models\Reviews;
use Illuminate\Http\JsonResponse;


use App\Http\Controllers\Api\bookingcontroller;

Route::middleware('auth:sanctum')->group(function () {

    // Notification logs (history)
    Route::get('/v1/notifications', [NotificationController::class, 'index']);
    Route::patch('/v1/notifications/{id}/read', [NotificationController::class, 'markRead']);
    Route::patch('/v1/notifications/read-all', [NotificationController::class, 'markAllRead']);

});


Route::get('/doctors/nearby', [DoctorController::class, 'getNearbyDoctors']);

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
Route::get('/doctors/{doctor_id}/slots', [bookingcontroller::class, 'availableSlots']);
Route::post('/appointments/book', [bookingcontroller::class, 'bookslot']);
    //  ->middleware('auth:sanctum'); 
    Route::get('/appointments/my', [bookingcontroller::class, 'myBookings']);
Route::delete('/mybooking/{id}/cancel/',[bookingcontroller::class,'cancel']);
Route::put('booking/{id}/update',[bookingcontroller::class,'update']);
Route::get('doctorBookings',[bookingcontroller::class,'doctorBookings']);