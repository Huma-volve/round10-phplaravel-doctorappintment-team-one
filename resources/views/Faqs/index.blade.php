@extends('master')

@section('title', ' FAQs  ')

@section("content")


<div class="col-12" style="margin-top: 10px;">
          @if ($submitMessage == "Delete Question Succefully")
                    <div class="alert alert-success" role="alert">
                         {{$submitMessage}}
                    </div>
               

          @elseif($submitMessage == "Delete Question Faild")
               <div class="alert alert-danger" role="alert">
                    {{$submitMessage}}
               </div>
          @endif

     </div>

<div class="col-12" style="margin-top: 10px; padding-right: 10px;">
     <div class="bg-light rounded h-100 p-4">
          <div class="row">
               <div class="col-10">
                    <h6 class="mb-4">FAQS</h6>
               </div>
               <div class="col-2">
                    <a href="/Faqs/create" type="button" class="btn btn-primary m-2">Add</a>
               </div>
          </div>
          <div class="table-responsive">
               <table class="table">
                    <thead>
                    <tr>
                         <th scope="col">#</th>
                         <th scope="col">Questions</th>
                         <th scope="col">Answer</th>
                         
                         <th scope="col">Actives</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach( $allQuestions as $index => $question )
                    
                         
                         <tr>
                              <th >{{ $index + 1 }}</th>
                              <td>{{$question->question}}</td>
                              <td>{{$question->answer}}</td>
                              
                              <td>
                                   
                                        
                                   <form action="/Faqs/{{ $question->id }}" method="POST">
                                        <a href="/Faqs/{{ $question->id }}/edit" type="button" style="color: white;" class="btn btn-info m-2">Edit</a>
                                             
                                             @csrf
                                             @method('DELETE')
                                             
                                             <button type="submit" class="btn btn-danger m-2">delete</button>
                                        </form>
                                        
                                   
                              </td>
                         </tr>

                    @endforeach
                   
                    </tbody>
               </table>
          </div>
     </div>
</div>
    
@endsection

