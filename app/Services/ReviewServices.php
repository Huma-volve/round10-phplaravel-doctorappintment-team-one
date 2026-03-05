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
               // send notifiaction services
               $this->notificationService->notify(
                    $data->doctor_id,
                    "review", 
                    'email' , 
                    'Review Doctor' , 
                    $data->comment , 
                    ['rating'=>$data->rating , 'comment'=>$data->comment]
               );

               return  true ;
          }else{
               return false ;
          }
    }
}
