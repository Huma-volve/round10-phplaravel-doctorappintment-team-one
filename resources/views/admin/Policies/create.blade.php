@extends('master')

@section('title', ' Policies Create ')

@section("content")


<form action="/admin/Policies" method="POST">
     @csrf
     <div class="col-12" style="margin-top: 10px; padding-right: 10px;">

          <div class="bg-light rounded h-100 p-4">
               <div class="row">
                    <div class="col-10" style="margin-top: 20px;">
                         <h6 class="mb-4">Policy Create</h6>
                    </div>
                    <div class="col-2">
                         <a href="/admin/Policies"> All Policies </a> <br>
                         <button type="submit" class="btn btn-primary m-2">save</button>
                    </div>
               </div>
               <div class="table-responsive">
                    
                         
                         <div class="mb-3">
                              <label for="exampleInputEmail1" class="form-label">Title</label>
                              <input type="text" class="form-control" name="title" >
                              @error('title')
                                   <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                        {{$message}}
                                   </div>
                              @enderror
                              
                              
                         </div>
                         <div class="mb-3">
                              <label for="exampleInputPassword1" class="form-label">Content</label>
                              <textarea name="contentent" class="form-control" col="10"></textarea>
                              @error('contentent')
                                   <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                        {{$message}}
                                   </div>
                              @enderror
                         </div>
                         
                         
          </div>
     </div>
     <div class="col-12" style="margin-top: 10px;">
          @if ($submitMessage == "Created Policy Succefully")
                    <div class="alert alert-success" role="alert">
                         {{$submitMessage}}
                    </div>
               

          @elseif($submitMessage == "Created Policy Faild")
               <div class="alert alert-danger" role="alert">
                    {{$submitMessage}}
               </div>
          @endif

     </div>
     

</form>
    
@endsection

