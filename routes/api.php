<?php

use App\Http\Controllers\Api\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

route::post('/doctor-search' , [SearchController::class , 'search_for_doctor']);
