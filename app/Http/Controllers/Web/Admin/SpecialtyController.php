<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::latest()->get();

        return view('admin.specialties.index',compact('specialties'));
    }

    public function create()
    {
        return view('admin.specialties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255|unique:specialties',
        ]);

        Specialty::create([
            'name'=>$request->name
        ]);

        return redirect()
            ->route('admin.specialties.index')
            ->with('success','Specialty created successfully');
    }
}
