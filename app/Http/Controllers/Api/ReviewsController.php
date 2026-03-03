<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewStoreRequest;
use App\Models\Review;
use App\Models\Reviews;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   
   
public function getReview()
{
   
    $reviews = Review::all();

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
    public function show($id)
    {
        $reviews = Review::find($id);
        return  response()->json([
                'status' => true,
                'message' => 'show rewiew ' ,
                'reviewData' => $reviews
                ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( $id , Request $request )  
    {
        $data['comment'] = $request->comment ;
        $data['rating'] = $request->rating ;
        $data['updated_at'] = now();
        $reviews = Review::find($id);
        $reviews->update($data);

    
         if($reviews){

            return  response()->json([
                'status' => true,
                'message' => 'updated rewiew seccuess'
                ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'updated rewiew faild'
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $reviews = Review::find($id);
        $reviews->delete();

    
         if($reviews){

            return  response()->json([
                'status' => true,
                'message' => 'delete rewiew seccuess'
                ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'delete rewiew faild'
                ]);
        }
    }
}
