@extends('master')

@section('title')

patient
@stop

@section("content")
@if (session()->has('delete'))
    <div class="alert alert-success">
        The user deleted successfully.
    </div>
@endif
@if (session()->has('success'))
    <div class="alert alert-success">
User status updated successfully
    </div>
@endif
    <div class="col-12">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4"> patient Table</h6>
                            
                            <div class="table-responsive">
                                   <form class="d-none d-md-flex ms-4" method="GET" action="{{ route('showpatient') }}">
          <input class="form-control border-0" type="search" name="search" placeholder="Search by name " value="{{ request('search') }}">
     </form>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col"> Email</th>
                                            <th scope="col">phone</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">photo</th>
                                            <th scope="col">birthdate</th>
                                            <th scope="col">operation</th>
                                            
                                        </tr>
                                        
                                    </thead>
                                    <tbody>
                                        @foreach ($patient as $p )
                                            
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $p->name }}</td>
                                            <td>{{ $p->email }}</td>
                                            <td>{{ $p->phone }}</td>
                                            <td>@if ($p->status=='active')
														<span class="text-success">{{$p->status}}</span>
													@elseif ($p->status=='banned')
														<span class="text-danger">{{$p->status}}</span>
														@else
														<span class="text-warning">{{$p->status}}</span>
													@endif
                                                </td>
                                                <td>
                                                    @if ($p->photo_url)
                                              <img src="{{ url('uploads/profile_photos/' . $p->photo_url) }}" alt="Profile Photo" width="50" height="50">

                                                        @else
                                                        <img src="{{ asset('dashboard/img/User-avatar-profile-icon-Graphics-17068385-1.jpg') }}" alt="Profile Photo" width="50" height="50">

                                                    @endif
                                                </td>
                                            <td>@if ($p->birthdate==null)
														  <span> not found 
                                                            birthdate </span>
														  @else
															{{$p->birthdate}}
														  
													@endif</td>
                                                    <td>
                                                 <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                      data-bs-toggle="modal"
                                                  data-bs-target="#delete{{$p->id}}"

                                                        title="delete"><i class="las la-trash"></i></a>
                                                 <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
                                                      data-bs-toggle="modal"
                                                  data-bs-target="#edit{{$p->id}}"

                                                        title="edit status"><i class="las la-pen"></i></a>
                                                        
                                                    </td>
                                        </tr>
                                                                                
                                        @endforeach
                                                                            </tbody>

@foreach ($patient as $p)
    
                                            <div class="modal" id="delete{{$p->id}}">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title">Delete User </h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    <span aria-hidden="true">&times;</span>
</button>
                                </div>
                                <form action="{{route('deletepatient', $p->id)}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-body">
                                        <p> Do you sure Delete the User</p><br>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">close</button>
                                        <button type="submit" class="btn btn-danger">delete</button>
                                    </div>
                            </div>
                            </form>
	
                        </div>
                    </div>


                    {{--                           Edit status      --}}
                                            <div class="modal" id="edit{{$p->id}}">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title">Edit Status </h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    <span aria-hidden="true">&times;</span>
</button>
                                </div>
                                <form action="{{route('editstatus', $p->id)}}" method="post">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body">
                                        <label for="status{{ $p->id }}">Status</label>
                                        <select name="status"  id="status{{$p->id}}" class="form-control">
                                             <option value="{{ $p->status }}" selected>{{ $p->status }}</option>

                                                   
                                 @if($p->status != 'active')
                                 <option value="active">Active</option>
                                  @endif
                                   @if($p->status != 'suspended')
                                              <option value="suspended">suspended</option>
                                         @endif
                                            @if($p->status != 'banned')
                                          <option value="banned">banned</option>
                                        @endif
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">close</button>
                                        <button type="submit" class="btn btn-info">Edit</button>
                                    </div>
                            </div>
                            </form>
	
                        </div>
                    </div>
                              @endforeach
       


                                </table>
                            </div>
                        </div>
                    </div>
    
              
@endsection

