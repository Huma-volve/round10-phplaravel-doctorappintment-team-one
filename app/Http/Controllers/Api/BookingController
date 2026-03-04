<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Models\DoctorTimeSlot;
use App\Models\Messages;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Symfony\Component\CssSelector\Node\FunctionNode;
use App\Http\Controllers\Controller;

class bookingcontroller extends Controller
{
  
    public function availableSlots($doctor_id)
    {
        $slots=DoctorTimeSlot::where('doctor_id','=', $doctor_id)->where('status','=','available')->get();
        return response()->json($slots);
    }


    public function bookslot(Request $request)
    {
        $valdiate=$request->validate([
            'id'=>'required|exists:doctor_time_slots,id',
               'user_id' => 'required|exists:users,id'
        ]);
      $slots=DoctorTimeSlot::findOrFail($valdiate['id']);
      if($slots->status !='available'){
        return response()->json([
           'message' => 'This time slot is not available.' 
        ],400);
      }


      Booking::create([
        'patient_id'=>$valdiate['user_id'],
        'doctor_id'=>$slots->doctor_id,
        'time_slot_id'=>$slots->id,
        'status'=>'confirmed',
        'payment_method'=>$request->payment_method ?? 'cash',
        'amount_cents' => 0,
        'starts_at_utc' => $slots->starts_at_utc,  
    'ends_at_utc'   => $slots->ends_at_utc,  
        'payment_status' => 'unpaid',
        'currency' => 'USD'
      ]);
      $slots->status='booked';

      $slots->save();
       return response()->json([
           'message' => 'Appointment booked successfully',
           'slots'=>$slots, 
        ],201);
    }

   public function myBookings(Request $request)
{
    $validate = $request->validate([
        'user_id' => 'required|exists:users,id'
    ]);

    $bookings = Booking::where('patient_id', $validate['user_id'])->get();

    if ($bookings->isEmpty()) {
        return response()->json([
            'message' => 'No bookings found.'
        ], 404);
    }

    return response()->json($bookings, 200);
}

public function cancel(Request $request, $id)
{
    $valdiate = $request->validate([
        'user_id' => 'required|exists:users,id'
    ]);

    $booking = Booking::where('id', $id)
        ->where('patient_id', $valdiate['user_id'])
        ->first();

    if (!$booking) {
        return response()->json([
            'message' => 'This booking is not for you or does not exist.'
        ], 403);
    }

 

  
        $slot = DoctorTimeSlot::find($booking->time_slot_id);
        if ($slot) {
            $slot->status = 'available';
            $slot->save();
        }

        $booking->status = 'cancelled_by_patient';
        $booking->save();


    return response()->json([
        'message' => 'Appointment cancelled successfully.',
    ], 200);
}



    
   public function update(Request $request, $id)
{
    $valdiate = $request->validate([
        'user_id' => 'required|exists:users,id',
        'new_booking_id' => 'required|exists:doctor_time_slots,id'
    ]);

    $oldBooking = Booking::where('id', $id)
        ->where('patient_id', $valdiate['user_id'])
        ->first();

    if (!$oldBooking) {
        return response()->json([
            'message' => 'This booking is not for you or does not exist.'
        ], 403);
    }

    
    $newSlot = DoctorTimeSlot::findOrFail($valdiate['new_booking_id']);

    if ($oldBooking->doctor_id != $newSlot->doctor_id) {
        return response()->json([
            'message' => 'New slot must be with the same doctor.'
        ], 400);
    }

    if ($newSlot->status != 'available') {
        return response()->json([
            'message' => 'This time slot is not available.'
        ], 400);
    }

 
        $oldSlot = DoctorTimeSlot::find($oldBooking->time_slot_id);
        if ($oldSlot) {
            $oldSlot->status = 'available';
            $oldSlot->save();
        }

       
        $oldBooking->time_slot_id = $newSlot->id;
        $oldBooking->starts_at_utc = $newSlot->starts_at_utc;
        $oldBooking->ends_at_utc = $newSlot->ends_at_utc;
        $oldBooking->status = 'rescheduled';
        $oldBooking->save();

        $newSlot->status = 'booked';
        $newSlot->save();
   
    return response()->json([
        'message' => 'Appointment rescheduled successfully.',
        'new_booking' => $oldBooking,
    ], 200);
}


public function doctorBookings(Request $request)
{
    $validate = $request->validate([
        'doctor_id' => 'required|exists:doctors,id'
    ]);

    $bookings = Booking::where('doctor_id', $validate['doctor_id'])->get();

    if ($bookings->isEmpty()) {
        return response()->json([
            'message' => 'No bookings found.'
        ], 404);
    }

    return response()->json($bookings, 200);
}
 
}





