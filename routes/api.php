<?php
use App\Http\Controllers\Notification\NotificationController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {

    // Notification logs (history)
    Route::get('/v1/notifications', [NotificationController::class, 'index']);
    Route::patch('/v1/notifications/{id}/read', [NotificationController::class, 'markRead']);
    Route::patch('/v1/notifications/read-all', [NotificationController::class, 'markAllRead']);

});