<?php


namespace App\Http\Controllers\dashboard;
use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\PoliciesRequest;
use App\Models\PolicyModel;
use Illuminate\Http\Request;

class PoliciesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allPolicies = PolicyModel::get();
        $submitMessage = "";
        return view('Policies.index' , compact('allPolicies','submitMessage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $submitMessage = "";
        
        return view('Policies.create' , compact('submitMessage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PoliciesRequest $request)
    {
        $dataPolicy = [
            
            'title' => $request->title ,
            'content' => $request->contentent ,
            'created_at' => now() ,
            'updated_at' => null ,
        ];

        $Policy = PolicyModel::create($dataPolicy);
        if($Policy){
            $submitMessage = "Created Policy Succefully";
            return view('Policies.create' , compact('submitMessage'));
        }else {
            $submitMessage = "Created Policy Faild";
            return view('Policies.create' , compact('submitMessage'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $Policy= PolicyModel::findOrFail($id);
        
        $submitMessage = "";
        return view('Policies.edit' , compact('Policy' , 'submitMessage' ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PoliciesRequest $request, string $id)
    {
        $dataPolicy = [
            
            'title' => $request->title ,
            'content' => $request->contentent ,
            'updated_at' => now() ,
        ];
        
        $Policy = PolicyModel::findOrFail($id);

        $update = $Policy->update($dataPolicy);

        if($update){
            $submitMessage = "Update Policy Succefully";

            return view('Policies.edit' , compact('submitMessage' ,'Policy'));
        }else {
            $submitMessage = "Update Policy Faild";
            return view('Policies.edit' , compact('submitMessage', 'Policy'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Policy = PolicyModel::findOrFail($id);

        $delete = $Policy->delete();
        $allPolicies = PolicyModel::get(); 
        if($delete){
            $submitMessage = "Delete Policy Succefully";

            return view('Policies.index' , compact('submitMessage' ,'allPolicies'));
        }else {
            $submitMessage = "Delete Policy Faild";
            return view('Policies.index' , compact('submitMessage', 'allPolicies'));
        }
    }
}
