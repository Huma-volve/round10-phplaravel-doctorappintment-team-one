<?php

use App\Http\Controllers\Api\Bookingcontroller;
use App\Http\Controllers\BookingtableController;
use App\Http\Controllers\manage_userController;
use App\Http\Controllers\paymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::get('/', function () {
    return view('master');
});



/////////////// admin show users/////////////

Route::get('showpatient',[manage_userController::class,'index'])->name('showpatient');
Route::delete('deletepatient/{id}',[manage_userController::class,'delete'])->name('deletepatient');
Route::put('editstatus/{id}',[manage_userController::class,'edit'])->name('editstatus');
Route::get('showdoctor',[manage_userController::class,'showdoctor'])->name('showdoctor');
Route::delete('deletedoctor/{id}',[manage_userController::class,'deletedoctor'])->name('deletedoctor');
Route::put('editstatusdoctor/{id}',[manage_userController::class,'editdoctor'])->name('editstatusdoctor');

////////////////// Booking table //////////////////////////////////////////
Route::get('bookingtable',[BookingtableController::class,'index'])->name('bookingtable');

Route::delete('deleteBooking/{id}',[BookingtableController::class,'deleteBooking'])->name('deleteBooking');

////////////////////// payment table//////////////////////////
Route::get('paymenttable',[paymentController::class,'index'])->name('paymenttable');
Route::get('showPayment/{id}',[paymentController::class,'showPayment'])->name('showPayment');

Route::post('/test-login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $request->session()->regenerate();

    return response()->json([
        'message' => 'Logged in',
        'user' => $request->user(),
    ]);
})->withoutMiddleware([VerifyCsrfToken::class]);
