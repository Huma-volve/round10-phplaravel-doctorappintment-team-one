<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function getNearbyDoctors(Request $request){
        $location = [
            "lat" => $request->lat,
            "lng" => $request->lng
        ];

        $user = auth()->user() ?? null;

        $doctors = Doctor::withExists(['favorites as is_favorite' => function($favQuery) use ($user){
                if($user){
                    $favQuery->where('doctor_id', $user->id);
                }
            }])
            ->whereHas('clinics', function($query) use ($location){
                $query->nearby($location);
            })
            ->where('status', 'active')
            ->get();


        return response()->json([
            'status' => 'success',
            'message' => '',
            'data' => new DoctorResource($doctors),
        ], 200);
    }
}
