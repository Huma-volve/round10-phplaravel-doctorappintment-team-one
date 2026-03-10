@extends('master')

@section('title')
Doctor
@stop

@section("content")
@if (session()->has('delete'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        The user deleted successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        User status updated successfully
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="col-12">
    <div class="bg-light rounded h-100 p-4 shadow-sm">
        <h4 class="mb-4 text-primary fw-bold">Doctor Table</h4>

        <div class="mb-3">
            <a href="{{ route('admin.doctors.create') }}"
               class="btn btn-primary fw-bold"
               style="padding: 10px 20px; font-size: 14px;">
               <i class="las la-plus"></i> Add Doctor
            </a>
        </div>

        <div class="table-responsive">
            <form class="d-none d-md-flex ms-4 mb-3" method="GET" action="{{ route('showdoctor') }}">
                <input class="form-control border-1 rounded" type="search" name="search" placeholder="Search by name"
                       value="{{ request('search') }}" style="width: 250px;">
            </form>

            <table class="table table-hover table-bordered align-middle">
                <thead class="table">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Photo</th>
                        <th>Birthdate</th>
                        <th>License Number</th>
                        <th>Bio</th>
                        <th>Experience</th>
                        <th>Verification Status</th>
                        <th>Verification Notes</th>
                        <th>Clinics</th>
                        <th>Operation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Doctor as $D)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $D->name }}</td>
                            <td>{{ $D->email }}</td>
                            <td>{{ $D->phone }}</td>
                            <td>
                                @if ($D->status=='active')
                                    <span class="badge bg-success">{{$D->status}}</span>
                                @elseif ($D->status=='banned')
                                    <span class="badge bg-danger">{{$D->status}}</span>
                                @else
                                    <span class="badge bg-warning">{{$D->status}}</span>
                                @endif
                            </td>
                            <td>
                                @if ($D->photo_url)
                                    <img src="{{ url('uploads/profile_photos/' . $D->photo_url) }}" alt="Profile Photo"
                                         width="50" height="50" class="rounded-circle border">
                                @else
                                    <img src="{{ asset('dashboard/img/User-avatar-profile-icon-Graphics-17068385-1.jpg') }}"
                                         alt="Profile Photo" width="50" height="50" class="rounded-circle border">
                                @endif
                            </td>
                            <td>{{ $D->birthdate ?? 'N/A' }}</td>
                            <td>{{ $D->doctor->license_number ?? 'N/A' }}</td>
                            <td>{{ $D->doctor->bio ?? 'N/A' }}</td>
                            <td>{{ $D->doctor->years_of_experience ?? 'N/A' }}</td>
                            <td>{{ $D->doctor->verification_status ?? 'N/A' }}</td>
                            <td>{{ $D->doctor->verification_notes ?? 'N/A' }}</td>
                            <td>
                                @if($D->doctor && $D->doctor->clinics->count() > 0)
                                    <ul class="mb-0">
                                        @foreach($D->doctor->clinics as $clinic)
                                            <li>{{ $clinic->name }} - {{ $clinic->address }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-sm btn-danger me-1" data-bs-toggle="modal" data-bs-target="#delete{{$D->id}}" title="Delete">
                                    <i class="las la-trash"></i>
                                </a>
                                <a class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#edit{{$D->id}}" title="Edit Status">
                                    <i class="las la-pen"></i>
                                </a>
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

