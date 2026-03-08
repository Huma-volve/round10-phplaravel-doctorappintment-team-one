<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use App\Models\Payment;
use Symfony\Component\CssSelector\Node\FunctionNode;

class PaymentController extends Controller
{
 public function createPaymentIntent(Request $request){
    $valdiated=$request->validate([
        'booking_id'=>'required|exists:Bookings,id'
    ]);
    $booking=Booking::findOrFail($valdiated['booking_id']);
      if ($booking->status != 'pending_payment') {
            return response()->json([
                'message' => 'Booking is not pending payment.'
            ], 400);
        
 }
   Stripe::setApiKey(config('services.stripe.secret'));

        $paymentIntent = PaymentIntent::create([
            'amount'   => $booking->amount_cents,
            'currency' => $booking->currency,
            'metadata' => [
                'booking_id' => $booking->id,
                'patient_id' => $booking->patient_id,
            ],
        ]);
             Payment::create([
            'booking_id'           => $booking->id,
            'provider'             => 'stripe',
            'provider_payment_id'  => $paymentIntent->id,
            'provider_customer_id' => '',
            'status'               => 'initiated',
            'amount_cents'         => $booking->amount_cents,
            'refunded_cents'       => 0,
            'currency'             => $booking->currency,
            'meta'                 => json_encode($paymentIntent),
        ]);

        return response()->json([
            'client_secret'     => $paymentIntent->client_secret,
            'payment_intent_id' => $paymentIntent->id,
        ], 201);

 }
 
 public function webhook (Request $request){
    $pay=$request->getContent();
    $event=json_decode($pay,true);
    if($event['type']=='payment_intent.succeeded'){
         $paymentIntentId = $event['data']['object']['id'];
         $payment=Payment::where('provider_payment_id',$paymentIntentId)->first();
         if($payment){
         $payment->status = 'succeeded';
            $payment->save();
$booking = Booking::findOrFail($payment->booking_id);
            $booking->status='confirmed';
            $booking->payment_status ='paid';
            $booking->save();
            
            // Notify patient of successful payment
            app(NotificationService::class)->notify(
                $booking->patient_id,
                'payment',
                'in_app',
                'Payment Successful',
                'Your payment has been processed successfully. Your booking is confirmed.',
                ['booking_id' => $booking->id,
                 'payment_id' => $payment->id]
            );
         }
    }
        return response()->json(['message' => 'Webhook received.'], 200);

 }
 
 
 
 }
