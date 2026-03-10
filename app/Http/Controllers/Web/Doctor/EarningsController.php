<?php

namespace App\Http\Controllers\Web\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EarningsController extends Controller
{
    public function index(Request $request){
        $statuses = ['unpaid','paid','refunded','partially_refunded'];

        $earningSummary = Booking::where('doctor_id',auth()->user()->doctor->id)
            ->selectRaw("COUNT(*) as total_orders, SUM(amount_cents) as total_amount_cents");

        foreach ($statuses as $status) {
            $earningSummary->selectRaw("SUM(CASE WHEN payment_status = '$status' THEN 1 ELSE 0 END) as {$status}_count");
            $earningSummary->selectRaw("SUM(CASE WHEN payment_status = '$status' THEN amount_cents ELSE 0 END) as {$status}_amount_cents");
        }

        $earningSummary = $earningSummary->first();

        
        $bookings = Booking::where('doctor_id',auth()->user()->doctor->id)->paginate(15);
        
        return view('doctor.earnings.index',compact('earningSummary','bookings'));
    }
}
