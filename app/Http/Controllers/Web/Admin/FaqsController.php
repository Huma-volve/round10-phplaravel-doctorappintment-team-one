<?php

namespace App\Http\Controllers\Web\Admin;
use App\Http\Controllers\Controller;

use App\Http\Requests\dashboard\FaqsRequest;
use App\Models\FaqModel;
use Illuminate\Http\Request;

class FaqsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $allQuestions = FaqModel::get();
        $submitMessage = "";
        return view('admin.Faqs.index' , compact('allQuestions','submitMessage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $submitMessage = "";
        
        return view('admin.Faqs.create' , compact('submitMessage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaqsRequest $request)
    {
        
        $dataFaq = [
            
            'question' => $request->question ,
            'answer' => $request->answer ,
            'created_at' => now() ,
            'updated_at' => null ,
        ];

        $Faq = FaqModel::create($dataFaq);
        if($Faq){
            $submitMessage = "Created Question Succefully";
            return view('admin.Faqs.create' , compact('submitMessage'));
        }else {
            $submitMessage = "Created Question Faild";
            return view('admin.Faqs.create' , compact('submitMessage'));
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $Faq = FaqModel::findOrFail($id);
        
        $submitMessage = "";
        return view('admin.Faqs.edit' , compact('Faq' , 'submitMessage' ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqsRequest $request, string $id)
    {
        $dataFaq = [
            
            'question' => $request->question ,
            'answer' => $request->answer ,
            'updated_at' => now() ,
        ];
        
        $Faq = FaqModel::findOrFail($id);

        $update = $Faq->update($dataFaq);

        if($update){
            $submitMessage = "Update Question Succefully";

            return view('admin.Faqs.edit' , compact('submitMessage' ,'Faq'));
        }else {
            $submitMessage = "Update Question Faild";
            return view('admin.Faqs.edit' , compact('submitMessage', 'Faq'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Faq = FaqModel::findOrFail($id);

        $delete = $Faq->delete();
        $allQuestions = FaqModel::get(); 
        if($delete){
            $submitMessage = "Delete Question Succefully";

            return view('admin.Faqs.index' , compact('submitMessage' ,'allQuestions'));
        }else {
            $submitMessage = "Delete Question Faild";
            return view('admin.Faqs.index' , compact('submitMessage', 'allQuestions'));
        }
    }
}
