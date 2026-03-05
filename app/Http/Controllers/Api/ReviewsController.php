<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReviewStoreRequest;
use App\Models\Review;
use App\Services\ReviewServices;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ReviewsController extends Controller
{
    
   protected $reviewServices;
   public function __construct(ReviewServices $reviewServices )
    {
         $this->reviewServices = $reviewServices;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function show($id)
    {
    
        $reviews = Review::where('doctor_id',$id)->get();

        return response()->json([
            'status' => true,
            'count' => $reviews->count(),
            'data' => $reviews
        ], 201);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewStoreRequest $request)
    {

        $user = auth()->user() ?? User::first();
        $data = $request ; 
        $data['paintentId'] = $user->id;

        $respose = $this->reviewServices->store($data);
        
        if($respose == true){
            return response()->json([
                'status' => true,
                'message' => 'created rewiew seccuess'
                ], 200);
        }else{
            
            return response()->json([
                    'status' => false,
                    'message' => 'created rewiew faild'
                    ], 401);
        }

       
    }

    
    
    
}
