<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\LocationNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Doctor\DoctorDetailsResource;
use App\Http\Resources\Doctor\DoctorHomeResource;
use App\Models\Doctor;
use App\Models\SearchHistory;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function getNearbyDoctors(Request $request){
        try{
            $request->validate([
                'lat' => ['required'],
                'lng' => ['required'],
                'radius' => ['sometimes', 'integer', 'min:10', 'max:100000'],
            ]);


            $doctors = Doctor::with(['specialties', 'clinics'])
                ->withExists(['favorites as is_favorite' => function($favQuery){
                    $favQuery->where('doctor_id', auth()->id());
                }])
                ->whereHas('clinics', function($query) use ($request){
                    $query->nearby([
                        'lat' => $request->lat,
                        'lng' => $request->lng,
                        'radius' => $request->radius ?? 10
                    ]);
                })
                ->distinct()
                ->limit(15)
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'nearby_doctors_reterived',
                'data' => DoctorHomeResource::collection($doctors),
            ], 200);
        }catch(LocationNotFoundException $e){
            return response()->json([
                'status' => 'error',
                'message' => 'location_is_required',
            ]);
        }
    }

    public function getDoctor(Doctor $doctor){


        $doctor->load(['specialties', 'clinics', 'favorites'])
            ->loadCount(['bookings', 'reviews'])
            ->loadExists(['favorites as is_favorite' => function($favQuery){
                $favQuery->where('doctor_id', auth()->id());
            }]);

        // add doctor to search history for auth user
        SearchHistory::create([
            'patient_id'   =>1,
            'doctor_id'    => $doctor->id,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'doctor_reterived',
            'data' => new DoctorDetailsResource($doctor),
        ], 200);
    }
}
