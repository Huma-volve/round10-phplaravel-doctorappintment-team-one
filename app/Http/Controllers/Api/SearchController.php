<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SearchRequest;
use App\Models\Doctor;
use App\Models\SearchHistory;

class SearchController extends Controller
{
    // TODO whating for auth and collections for models

    public function index(){
        $user_id = auth()->id();

//        $search_histories = SearchHistory::with('doctor')
//            ->where('patient_id', $user_id)
//            ->latest()
//            ->get();
        $search_histories=SearchHistory::where('patient_id',$user_id)->get();

        return response()->json([
            'data' => $search_histories
        ]);
    }
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


        return response()->json(['data'=>$doctors] , 200);
    }

}
