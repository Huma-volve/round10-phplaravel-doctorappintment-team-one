<?php

namespace App\Http\Controllers\DoctorPanel;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use function Pest\Laravel\session;

class DoctorPanelController extends Controller
{
    public function index(){
 $doctorUser = Auth()->user(); 
    $doctor = $doctorUser->doctor; 

    $bookings = Booking::with(['patient', 'clinic', 'timeSlot'])
                        ->where('doctor_id', $doctor->id) 
                        ->paginate(10)     ;
       return view('doctorpanal.doctorpanal',compact('bookings'));
    }

    public function cancelBooking(Booking $booking){
  $booking->status = 'cancelled_by_doctor';
        $booking->save();
        
        
      return redirect()->back()
       ->with('cancel','the booking is cancel by doctor');;
        
        }


 public function EditBooking(Booking $booking){
   $doctorUser=Auth()->user();
   $doctor=$doctorUser->doctor;
return view('doctorpanal.edit',compact('booking'));
 }

 public function updateBooking(Request $request, Booking $booking){
       $request->validate([
        'starts_at_utc'=>'required|date',
        'ends_at_utc'=>'required|date|after:starts_at_utc',
        'status'=>'required|in:pending_payment,confirmed,completed,cancelled_by_patient,cancelled_by_doctor,rescheduled'
    ]);
   //  dd($request);


    $booking->update([
      'starts_at_utc'=>$request->starts_at_utc,
      'ends_at_utc'=>$request->ends_at_utc,
      'status'=>$request->status
      ]);

   return redirect()->route('doctorpanal')
       ->with(key: 'update',value: 'the booking is updated');;
 }
 public function payment($id){
   $booking=Booking::with(['payment'])->findOrFail($id);
    return view('doctorpanal.payment', compact('booking'));

 }
}
 