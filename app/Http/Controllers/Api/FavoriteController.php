<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Doctor\DoctorHomeResource;
use App\Models\User;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function addToFavorite(Request $request){
        $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id']
        ]);

        $user = auth()->user() ?? User::first();

        if($user->favorites()->where('doctor_id', $request->doctor_id)->exists()){
            return response()->json([
                'status' => 'error',
                'message' => 'Doctor already added to favorite',
            ], 400);
        }


        $user->favorites()->attach([
            'doctor_id' => $request->doctor_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Doctor added to favorite successfully',
        ], 200);
    }

    public function removeFromFavorite(Request $request){
        $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id']
        ]);

        $user = auth()->user() ?? User::first();

        $user->favorites()->detach([
            'doctor_id' => $request->doctor_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Doctor removed from favorite successfully',
        ], 200);
    }

    public function listFavorites(){
        $user = auth()->user() ?? User::first();

        return response()->json([
            'message' => 'List of favorite doctors',
            'data' => DoctorHomeResource::collection($user->favorites)
        ], 200);
    }
}
