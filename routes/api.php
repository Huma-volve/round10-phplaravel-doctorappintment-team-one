<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MeNotificationsController;
use App\Http\Controllers\Api\NotificationPreferencesController;

Route::middleware('auth:sanctum')->group(function () {

    // notification logs
    Route::get('/me/notifications', [MeNotificationsController::class, 'index']);
    Route::patch('/notifications/{id}/read', [MeNotificationsController::class, 'markRead']);
    Route::patch('/me/notifications/read-all', [MeNotificationsController::class, 'markAllRead']);

});