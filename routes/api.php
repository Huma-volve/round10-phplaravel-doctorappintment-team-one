<?php

use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\ReviewsController;
use App\Http\Controllers\Api\SearchController;
use App\Models\Reviews;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('reviews/getAll', [ReviewsController::class , 'getReview']);
Route::apiResource('reviews', ReviewsController::class);


Route::post('/messages', [MessageController::class, 'store']);  // إرسال الرسائل
Route::post('/messages/reply/{conversation_id}', [MessageController::class, 'reply']); // الرد على الرسالة
Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
Route::post('/conversations/{conversation}/read', [ConversationController::class, 'markAsRead']);
Route::get('/conversations/{conversation}/unread', [ConversationController::class, 'unreadCount']);
Route::post('/messages/media', [MessageController::class,'sendMediaMessage']);
Route::post('/conversations/{conversation_id}/favorite', [ConversationController::class, 'toggleFavorite']);
Route::post('/conversations/{conversation_id}/archive', [ConversationController::class, 'archiveConversation']);
