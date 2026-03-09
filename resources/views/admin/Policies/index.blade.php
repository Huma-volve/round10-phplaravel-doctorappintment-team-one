@extends('master')

@section('title', ' Policies  ')

@section("content")


<div class="col-12" style="margin-top: 10px;">
          @if ($submitMessage == "Delete Policy Succefully")
                    <div class="alert alert-success" role="alert">
                         {{$submitMessage}}
                    </div>
               

          @elseif($submitMessage == "Delete Policy Faild")
               <div class="alert alert-danger" role="alert">
                    {{$submitMessage}}
               </div>
          @endif

     </div>

<div class="col-12" style="margin-top: 10px; padding-right: 10px;">
     <div class="bg-light rounded h-100 p-4">
          <div class="row">
               <div class="col-10">
                    <h6 class="mb-4">Policies</h6>
               </div>
               <div class="col-2">
                    <a href="/Policies/create" type="button" class="btn btn-primary m-2">Add</a>
               </div>
          </div>
          <div class="table-responsive">
               <table class="table">
                    <thead>
                    <tr>
                         <th scope="col">#</th>
                         <th scope="col">Title</th>
                         <th scope="col">Content</th>
                         
                         <th scope="col">Actives</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach( $allPolicies as $index => $policy )
                    
                         
                         <tr>
                              <th >{{ $index + 1 }}</th>
                              <td>{{$policy->title}}</td>
                              <td>{{$policy->content}}</td>
                              
                              <td>
                                   
                                        
                                   <form action="/admin/Policies/{{ $policy->id }}" method="POST">
                                        <a href="/admin/Policies/{{ $policy->id }}/edit" type="button" style="color: white;" class="btn btn-info m-2">Edit</a>
                                             
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

