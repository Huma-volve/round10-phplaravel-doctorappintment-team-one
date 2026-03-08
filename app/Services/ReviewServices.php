<?php

namespace App\Services;

use App\Models\Review;
use App\Services\NotificationService;

class ReviewServices
{

      protected $notificationService;
     public function __construct(NotificationService $notificationService )
    {
         $this->notificationService = $notificationService;
    }
    public function store($data ){
        
         
          $dataReview = [
            
                'booking_id' => $data->booking_id ,
                'patient_id' =>$data->paintentId ,
                'doctor_id' => $data->doctor_id ,
                'rating' => $data->rating ,
                'comment' => $data->comment ,
                'created_at' => now() ,
                'updated_at' => null ,
          ];
          // save reviews 
          $reviews = Review::create($dataReview);
          if($reviews){
               // Get doctor's user_id from Doctor model
               $doctor = \App\Models\Doctor::find($data->doctor_id);
               
               // send notification to doctor
               $this->notificationService->notify(
                    $doctor->user_id,
                    "review", 
                    'in_app' , 
                    'New Review' , 
                    'You received a new review from a patient: ' . substr($data->comment, 0, 50) . '...' , 
                    ['rating'=>$data->rating , 'comment'=>$data->comment, 'review_id'=>$reviews->id]
               );

               return  true ;
          }else{
               return false ;
          }
    }
}
