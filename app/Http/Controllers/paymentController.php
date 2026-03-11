<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class paymentController extends Controller
{
    public function index(){
       $payments = Payment::with(['booking.patient', 'booking.doctor'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
                      return view('show.showpayment',compact('payments'));
    }
    public function showPayment($id){
 $payment = Payment::where('booking_id', $id)->first();
 dd($payment);
                       return view('show.showpaymentDetail',compact('payment'));

    }
}
