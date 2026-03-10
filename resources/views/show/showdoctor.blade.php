@extends('master')

@section('title')

Doctor
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
                            <h6 class="mb-4"> Doctor Table</h6>
                            
                            <div class="table-responsive">
                                   <form class="d-none d-md-flex ms-4" method="GET" action="{{ route('showdoctor') }}">
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
                                            <th scope="col">license_number</th>
                                            <th scope="col">bio</th>
                                            <th scope="col">years_of_experience</th>
                                            <th scope="col">verification_status</th>
                                            <th scope="col">verification_notes</th>
                                            <th scope="col">clinics</th>
                                            <th scope="col">operation</th>
                                            
                                        </tr>
                                        
                                    </thead>
                                    <tbody>
                                        @foreach ($Doctor as $D)
                                            
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $D->name }}</td>
                                            <td>{{ $D->email }}</td>
                                            <td>{{ $D->phone }}</td>
                                            <td>@if ($D->status=='active')
														<span class="text-success">{{$D->status}}</span>
													@elseif ($D->status=='banned')
														<span class="text-danger">{{$D->status}}</span>
														@else
														<span class="text-warning">{{$D->status}}</span>
													@endif
                                                </td>
                                                <td>
                                                    @if ($D->photo_url)
                                              <img src="{{ url('uploads/profile_photos/' . $D->photo_url) }}" alt="Profile Photo" width="50" height="50">

                                                        @else
                                                        <img src="{{ asset('dashboard/img/User-avatar-profile-icon-Graphics-17068385-1.jpg') }}" alt="Profile Photo" width="50" height="50">

                                                    @endif
                                                </td>
                                            <td>@if ($D->birthdate==null)
														  <span> not found 
                                                            birthdate </span>
														  @else
															{{$D->birthdate}}
														  
													@endif</td>
                                                    <td>{{ $D->doctor->license_number??'N/A'}}</td>
                                                    <td>{{ $D->doctor->bio??'N/A' }}</td>
                                                    <td>{{ $D->doctor->years_of_experience ??'N/A'}}</td>
                                                    <td>{{ $D->doctor->verification_status??'N/A' }}</td>
                                                    <td>{{ $D->doctor->verification_notes ?? 'N/A' }}</td>
<td>
    @if($D->doctor && $D->doctor->clinics->count() > 0)
        <ul>
            @foreach($D->doctor->clinics as $clinic)
                <li>{{ $clinic->name }} - {{ $clinic->address }}</li>
            @endforeach
        </ul>
    @else
        N/A
    @endif
</td>
                                                    <td>
                                                 <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                      data-bs-toggle="modal"
                                                  data-bs-target="#delete{{$D->id}}"

                                                        title="delete"><i class="las la-trash"></i></a>
                                                 <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
                                                      data-bs-toggle="modal"
                                                  data-bs-target="#edit{{$D->id}}"

                                                        title="edit status"><i class="las la-pen"></i></a>
                                                        
                                                    </td>
                                        </tr>
                                                                                
                                        @endforeach
                                                                            </tbody>

@foreach ($Doctor as $D)
    
                                            <div class="modal" id="delete{{$D->id}}">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title">Delete Doctor </h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    <span aria-hidden="true">&times;</span>
</button>
                                </div>
                                <form action="{{ route('deletedoctor',$D->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-body">
                                        <p> Do you sure Delete the Doctor</p><br>
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
                                            <div class="modal" id="edit{{$D->id}}">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title">Edit Status </h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    <span aria-hidden="true">&times;</span>
</button>
                                </div>
                                <form action="{{ route('editstatusdoctor',$D->id) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body">
                                        <label for="status{{ $D->id }}">Status</label>
                                        <select name="status"  id="status{{$D->id}}" class="form-control">
                                             <option value="{{ $D->status }}" selected>{{$D->status }}</option>

                                                   
                                 @if($D->status != 'active')
                                 <option value="active">Active</option>
                                  @endif
                                   @if($D->status != 'suspended')
                                              <option value="suspended">suspended</option>
                                         @endif
                                            @if($D->status != 'banned')
                                          <option value="banned">banned</option>
                                        @endif
                                        </select>
                                    <label for="verification_status{{ $D->id }}">Verification Status</label>

<select name="verification_status" id="verification_status{{$D->id}}" class="form-control">
    <option value="{{ $D->doctor->verification_status }}" selected disabled>{{ $D->doctor->verification_status }}</option>
                                                 

    @if($D->doctor->verification_status!='approved')
        <option value="approved">approved</option>
    @endif
    @if($D->doctor->verification_status != 'rejected')
        <option value="rejected">rejected</option>
    @endif
    @if($D->doctor->verification_status != 'pending')
        <option value="pending">pending</option>
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

