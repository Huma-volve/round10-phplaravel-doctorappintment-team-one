<?php

use App\Http\Controllers\Web\Admin\DoctorController;
use App\Http\Controllers\Web\Admin\SpecialtyController;
use App\Http\Controllers\Web\Doctor\ProfileController;
use App\Http\Controllers\Web\Doctor\ScheduleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\Web\Auth\AuthController;
use App\Http\Controllers\Web\Doctor\EarningsController;
use App\Http\Controllers\Web\Doctor\PatientController;
use App\Http\Controllers\Web\Doctor\SettingController;

Route::get('/', function () {
    return view('master');
})->name('home');

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


Route::prefix('web/auth')->group(function () {
    Route::get('/login', [AuthController::class,'showLogin'])->name('login');
    Route::post('/login', [AuthController::class,'login']);
    Route::post('/logout', [AuthController::class,'logout'])->name('logout');
});


Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function(){

    Route::get('/doctors/create',[DoctorController::class,'create'])->name('doctors.create');

    Route::post('/doctors',[DoctorController::class,'store'])->name('doctors.store');

    Route::get('/specialties',[SpecialtyController::class,'index'])->name('specialties.index');
    Route::get('/specialties/create',[SpecialtyController::class,'create'])->name('specialties.create');
    Route::post('/specialties',[SpecialtyController::class,'store'])->name('specialties.store');


});

Route::middleware(['auth','role:doctor'])->prefix('doctor')->name('doctor.')->group(function(){

    Route::get('/profile',[ProfileController::class,'index'])->name('profile');
    Route::post('/change-password',[ProfileController::class,'changePassword'])->name('change.password');

//    Route::get('/schedules',[ScheduleController::class,'index'])->name('schedules.index');
    Route::post('/profile/specialties', [ProfileController::class, 'updateSpecialties'])
        ->name('profile.update.specialties');

    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])
        ->name('profile.update');

    Route::prefix('/patients')->name('patients.')->group(function(){
        Route::get('/',[PatientController::class, 'index'])->name('index');
        Route::get('/{patient}',[PatientController::class, 'show'])->name('show');
        Route::get('/{patient}/edit',[PatientController::class, 'edit'])->name('edit');
        Route::post('/{patient}/update',[PatientController::class, 'update'])->name('update');
        Route::get('/{patient}/delete',[PatientController::class, 'destroy'])->name('delete');
    });

    Route::prefix('/earnings')->name('earnings.')->group(function(){
        Route::get('/',[EarningsController::class, 'index'])->name('index');
    });

    Route::prefix('/settings')->name('settings.')->group(function(){
        Route::get('/',[SettingController::class, 'index'])->name('index');
        Route::post('/update',[SettingController::class, 'update'])->name('update');
    });

    Route::prefix('/clinics')->name('clinics.')->group(function(){
        Route::post('/create',[SettingController::class, 'create'])->name('create');
        Route::post('/edit',[SettingController::class, 'edit'])->name('edit');
        Route::delete('/delete',[SettingController::class, 'delete'])->name('delete');
    });
});
