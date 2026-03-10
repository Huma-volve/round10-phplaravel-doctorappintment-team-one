<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingtableController extends Controller
{
    public function index(Request $request){

    $query=$request->input('search');
        $booking=Booking::with(['patient','doctor.user','timeSlot','clinic'])->paginate(10);
        // dd($booking);
        return view('show.showBooking',compact('booking'));
        
    }
    public function deleteBooking($id){
            Booking::where('id','=',$id)->delete();
        session()->flash('delete');
            return redirect()->back();
    }
}
