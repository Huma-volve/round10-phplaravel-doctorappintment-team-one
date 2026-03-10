<?php



use App\Http\Controllers\Web\Admin\FaqsController;
use App\Http\Controllers\Web\Admin\PoliciesController;
use App\Http\Controllers\Api\Bookingcontroller;
use App\Http\Controllers\BookingtableController;
use App\Http\Controllers\manage_userController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\Web\Admin\DoctorController;
use App\Http\Controllers\Web\Admin\SpecialtyController;
use App\Http\Controllers\Web\Doctor\ProfileController;
use App\Http\Controllers\Web\Doctor\ScheduleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\Web\Admin\NotificationController;
use App\Http\Controllers\Web\Auth\AuthController;

Route::get('/', function () {
    return view('master');
})->name('home');



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

// Admin Routes

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

    /// Notifications
       Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/type/{type}', [NotificationController::class, 'getByType'])->name('notifications.by-type');
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');

    Route::resource('Faqs', FaqsController::class);
    Route::resource('Policies', PoliciesController::class);


});

Route::middleware(['auth','role:doctor'])->prefix('doctor')->name('doctor.')->group(function(){

    Route::get('/profile',[ProfileController::class,'index'])->name('profile');
    Route::post('/change-password',[ProfileController::class,'changePassword'])->name('change.password');

//    Route::get('/schedules',[ScheduleController::class,'index'])->name('schedules.index');
    Route::post('/profile/specialties', [ProfileController::class, 'updateSpecialties'])
        ->name('profile.update.specialties');

    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])
        ->name('profile.update');

    Route::post('/profile/clinics', [ProfileController::class, 'addClinic'])
        ->name('profile.add.clinic');
});
Route::middleware(['auth'])->group(function(){

  
    /// Notifications
       Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/type/{type}', [NotificationController::class, 'getByType'])->name('notifications.by-type');
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');



});
