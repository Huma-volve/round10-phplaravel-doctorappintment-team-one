<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewStoreRequest;
use App\Models\Review;
use App\Models\Reviews;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ReviewsController extends Controller
{
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
    ], 200);
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewStoreRequest $request)
    {

       
        $data = $request->all();
        $reviews = Review::create($data);
        if($reviews){

            return  response()->json([
                'status' => true,
                'message' => 'created rewiew seccuess'
                ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'created rewiew faild'
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    
    
}
