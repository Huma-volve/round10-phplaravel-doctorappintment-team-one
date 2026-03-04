<?php
use App\Http\Controllers\Api\bookingcontroller;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\DoctorProfileController;
use App\Http\Controllers\Api\MeNotificationsController;
use App\Http\Controllers\Api\NotificationPreferencesController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReviewsController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Notification\NotificationTestController;
use App\Models\Reviews;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {


    // Get all notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    // Mark one notification as read
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markRead']);
    // Mark all notifications as read
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead']);
    // Get user preferences
    Route::get('/notification-preferences', [NotificationController::class, 'preferences']);
    // Update preference (enable/disable)
    Route::patch('/notification-preferences', [NotificationController::class, 'updatePreference']);



    Route::get('/profile', [ProfileController::class,'show']);

    Route::patch('/profile', [ProfileController::class,'update']);

    Route::post('/profile/photo', [ProfileController::class,'uploadPhoto']);

    Route::patch('/profile/password', [ProfileController::class,'updatePassword']);


    Route::get('/doctor/profile', [DoctorProfileController::class, 'show']);
    Route::patch('/doctor/profile', [DoctorProfileController::class, 'update']);

});



Route::get('/doctors/nearby', [DoctorController::class, 'getNearbyDoctors']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('reviews/getAll', [ReviewsController::class , 'getReview']);
Route::apiResource('reviews', ReviewsController::class);





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