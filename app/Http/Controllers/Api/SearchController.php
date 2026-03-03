<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SearchRequest;
use App\Models\Doctor;
use App\Models\SearchHistory;

class SearchController extends Controller
{
    // TODO whating for auth and collections for models
    public function search_for_doctor(SearchRequest $request)
    {
        $doctors = Doctor::query()
            ->when($request->filled('name'), function ($q) use ($request) {
                $q->whereHas('user', function ($q2) use ($request) {
                    $q2->where('name', 'like', '%' . $request->name . '%');
                });
            })
            ->when($request->filled('speciality_id'), function ($q) use ($request) {
                $q->whereHas('specialties', function ($q2) use ($request) {
                    $q2->where('specialties.id', $request->speciality_id);
                });
            })
            ->with(['user', 'specialties','clinics'])
            ->paginate(10);

//        if($request->filled('speciality_id')){
//            SearchHistory::create([
//                'patient_id'   => auth()->id(),
//                'doctor_id'    => null,
//                'specialty_id' => $request->specialty_id,
//            ]);
//        }

        return response()->json(['data'=>$doctors] , 200);
    }

}
