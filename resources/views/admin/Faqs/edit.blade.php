@extends('master')

@section('title', ' FAQs  ')

@section("content")


<form action="{{ route('admin.Faqs.update', $Faq->id) }}" method="POST">
     
     @csrf
    @method('PUT')

     <div class="col-12" style="margin-top: 10px; padding-right: 10px;">

          <div class="bg-light rounded h-100 p-4">
               <div class="row">
                    <div class="col-10" style="margin-top: 20px;">
                         <h6 class="mb-4">FAQ Edit</h6>
                    </div>
                    <div class="col-2">
                         <a href="/admin/Faqs"> All Question </a> <br>
                         <button type="submit" class="btn btn-primary m-2">Update</button>
                    </div>
               </div>
               <div class="table-responsive">
                    
                        
                         <div class="mb-3">
                              <label for="exampleInputEmail1" class="form-label">Questions</label>
                              <input type="text" class="form-control" name="question"  value="{{ $Faq->question }}">
                              @error('question')
                                   <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                        {{$message}}
                                   </div>
                              @enderror
                              
                              
                         </div>
                         <div class="mb-3">
                              <label for="exampleInputPassword1" class="form-label">Answer</label>
                              <textarea name="answer" class="form-control" col="10">
                                   {{ $Faq->answer }}
                              </textarea>
                              @error('answer')
                                   <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                                        {{$message}}
                                   </div>
                              @enderror
                         </div>
                         
                         
          </div>
     </div>
     <div class="col-12" style="margin-top: 10px;">
          @if ($submitMessage == "Update Question Succefully")
                    <div class="alert alert-success" role="alert">
                         {{$submitMessage}}
                    </div>
               

          @elseif($submitMessage == "Update Question Faild")
               <div class="alert alert-danger" role="alert">
                    {{$submitMessage}}
               </div>
          @endif

     </div>
     

</form>
    
@endsection

