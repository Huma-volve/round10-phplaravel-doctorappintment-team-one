<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Booking;
use App\Models\Conversation;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(){
        $patients = User::where('role','patient')->count();
        $Doctors = User::where('role','doctor')->count();
        $Booking = Booking::count();
        $conversations = Conversation::count();

        $BookingData =  Booking::select(
            DB::raw('DATE(starts_at_utc) as date'),
            DB::raw('count(*) as total')
        )
        ->whereBetween('starts_at_utc', [now()->subDays(10), now()->addDays(4)])
        ->groupBy('date')
        ->orderBy('date')
        ->get();


        // إنشاء التواريخ من 10 ايام  قبل حتى 4 أيام بعد
        $dates = collect();
        for ($i = -2; $i <= 4; $i++) {
            $dates->push(Carbon::now()->addDays($i)); // هذا يكون كائن Carbon
        }

        // ملء القيم مع 0 للأيام التي لا يوجد فيها حجوزات
        $totals = $dates->map(function($date) use ($BookingData) {
            // تحويل Carbon إلى نص Y-m-d لمقارنة الـ BookingData
            $found = $BookingData->firstWhere('date', $date->format('Y-m-d'));
            return $found ? $found->total : 0;
        });

        // تحويل التواريخ إلى أيام (Mon, Tue, Wed...)
        $labelBooking = $dates->map(fn($d) => $d->format('D'))->toArray();
        $dataBooking = $totals;
        



                


        return view('admin.dashboard.index' , compact('Doctors', 'patients' ,'Booking','conversations' , 'labelBooking' , 'dataBooking' ));
    }
    public function doctorReviewReports(){
        $Doctors = Doctor::with('reviews')->with('user')->get();
        
        return view('admin.reports.index' , compact('Doctors' ));
    }
    public function doctorBookingReports(){
        $Doctors = Doctor::with('bookings')->with('user')->get();
        
        
        return view('admin.reports.bookingReport' , compact('Doctors' ));
    }

    public function doctorReviews(Request $request)
    {

        $doctorId = $request->doctor_id;
       
        if( $doctorId == 'all'){
            $reviews = Review::with('patient')
            ->leftJoin('doctors', 'reviews.doctor_id', '=', 'doctors.id')
            ->leftJoin('users', 'users.id', '=', 'doctors.user_id')
            ->select(
                'reviews.*',
                'users.name as doctor_name',                                                                            
            )
            ->get();

            
        }else{
            $reviews = Review::where('doctor_id', $doctorId)->with('patient')
            ->get();
        }

        

        
        return response()->json($reviews);
    }
    public function doctorBooking(Request $request)
    {

        $doctorId = $request->doctor_id;
       
        if( $doctorId == 'all'){
             $booking = Doctor::with(['bookings.patient' ,'bookings.timeSlot.clinic', 'bookings.doctor.user'])
            ->get();

            if ($booking[0]->bookings->count() == 0) {
                $booking['isEmpty'] = false;
            }else{
                $booking['isEmpty'] = true;
            }
        }else{
            $booking = Doctor::where('id', $doctorId)->with(['bookings.patient' ,'bookings.timeSlot.clinic'])
            ->get();

            if ($booking[0]->bookings->count() == 0) {
                $booking['isEmpty'] = false;
            }else{
                $booking['isEmpty'] = true;
            }
        }

        
        
        

        
        return response()->json($booking );
    }

    
}
