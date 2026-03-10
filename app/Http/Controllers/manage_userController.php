<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class manage_userController extends Controller
{


////////////////////////////// Patient ///////////////////////////////
    public function index(Request $request){
  $query=$request->input('search');
    if($query){
     $patient=User::where('role','=','patient')->where('name','like','%'.$query.'%')->get();
    }else{

        $patient=User::where('role','=','patient')->get();
        }
        $patient=$patient->sortBy('name');
        return view('show.showpainet',compact('patient'));
    }
    public function delete( $id){
        User::where('id','=',$id)->delete();
       session()->flash('delete','done');
return redirect()->route('showpatient');
    }
    public function edit( Request $request,$id){
           $request->validate([
        'status' => 'required|in:active,suspended,banned',
    ]);

    $user = User::findOrFail($id);
    $user->status = $request->input('status');
    $user->save();

    session()->flash('success');
    return redirect()->route('showpatient');
    }


//////////////////////Doctor ////////////////





public function showdoctor(Request $request){
     $query=$request->input('search');
    if($query){
     $Doctor=User::with('doctor.clinics')->where('role','=','doctor')->where('name','like','%'.$query.'%')->get();
    }else{

        $Doctor=User::with('doctor.clinics')->where('role','=','doctor')->get();
        }
        $Doctor=$Doctor->sortBy('name');
        // dd($Doctor);
        return view('show.showdoctor',compact('Doctor'));
}
    public function deletedoctor( $id){
        User::where('id','=',$id)->delete();
       session()->flash('delete','done');
return redirect()->route('showdoctor');
    }


    public function editdoctor( Request $request,$id){
           $request->validate([
        'status' => 'required|in:active,suspended,banned',
    ]);

    $user = User::findOrFail($id);
    $user->status = $request->input('status');
    $user->save();
  if ($user->doctor) {
        $user->doctor->verification_status = $request->verification_status;
        $user->doctor->save();
    }
    session()->flash('success');
    return redirect()->route('showdoctor');
    }







}
