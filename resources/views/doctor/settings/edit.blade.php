@extends('master')

@section('content')
    <div class="container-fluid pt-4 px-4">

        <div class="bg-light rounded p-4">

            <h4 class="mb-4">Doctor Profile</h4>

            {{-- Tabs --}}
            <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic"
                        type="button" role="tab">Basic Info</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="professional-tab" data-bs-toggle="tab" data-bs-target="#professional"
                        type="button" role="tab">Professional Info</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="clinics-tab" data-bs-toggle="tab" data-bs-target="#clinics" type="button"
                        role="tab">Clinics</button>
                </li>
            </ul>

            <div class="tab-content" id="profileTabsContent">

                {{-- Basic Info Tab --}}
                <div class="tab-pane fade show active" id="basic" role="tabpanel">

                    <form action="{{ route('doctor.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $doctor->name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $doctor->email }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $doctor->phone }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Birthdate</label>
                                <input type="date" name="birthdate" class="form-control" value="{{ $doctor->birthdate }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" name="photo" class="form-control">
                            @if($doctor->photo_url)
                                <img src="{{ asset('storage/' . $doctor->photo_url) }}" alt="Photo" class="img-thumbnail mt-2"
                                    style="width:100px">
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Save Basic Info</button>
                    </form>

                </div>

                {{-- Professional Info Tab --}}
                <div class="tab-pane fade" id="professional" role="tabpanel">
                    <form action="{{ route('doctor.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">License Number</label>
                            <input type="text" name="license_number" class="form-control"
                                value="{{ $doctor->license_number }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" rows="4">{{ $doctor->bio }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Years of Experience</label>
                            <input type="number" name="years_of_experience" class="form-control"
                                value="{{ $doctor->years_of_experience }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Save Professional Info</button>
                    </form>
                </div>

                {{-- Clinics Tab --}}
                <div class="tab-pane fade" id="clinics" role="tabpanel">
                    <a href="{{ route('doctor.clinics.create') }}" class="btn btn-success mb-3">Add Clinic</a>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Address</th>
                                <th>Session Duration (min)</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clinics as $clinic)
                                <tr>
                                    <td>{{ $clinic->id }}</td>
                                    <td>{{ $clinic->name }}</td>
                                    <td>{{ $clinic->start_time }}</td>
                                    <td>{{ $clinic->end_time }}</td>
                                    <td>{{ $clinic->address }}</td>
                                    <td>{{ $clinic->session_duration_minutes }}</td>
                                    <td>{{ number_format($clinic->session_price_cents / 100, 2) }} {{ $clinic->currency }}</td>
                                    <td>
                                        <a href="{{ route('doctor.clinics.edit', $clinic->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('doctor.clinics.delete', $clinic->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>
@endsection