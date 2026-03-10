@extends('master')


@section('content')
    <div class="container-fluid pt-4 px-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="d-flex justify-content-between mb-4">
            <h5 class="mb-0">Edit Patient</h5>
            <a href="{{ route('doctor.patients.show', $patient->id) }}" class="btn btn-sm btn-primary">Back</a>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4"> Edit Patient Profile </h5>
                <form method="POST" action="{{ route('doctor.patients.update', $patient->id) }}"> @csrf @method('PUT') <div
                        class="row"> <!-- Avatar -->
                        <div class="col-md-3 text-center"> <img
                                src="https://static.vecteezy.com/system/resources/thumbnails/020/416/057/small/man-icon-vector.jpg"
                                class="rounded-circle mb-3 border" style="width:120px;height:120px;object-fit:cover;">
                            <div> <input name="photo_url" type="file" class="form-control form-control-sm"> </div>
                        </div> <!-- Form Fields -->
                        <div class="col-md-9">
                            <div class="row g-3">
                                <div class="col-md-6"> <label class="form-label">Name</label> <input type="text" name="name"
                                        class="form-control" value="{{ old('name', $patient->name) }}"> </div>
                                <div class="col-md-6"> <label class="form-label">Email</label> <input type="email"
                                        name="email" class="form-control" value="{{ old('email', $patient->email) }}">
                                </div>
                                <div class="col-md-6"> <label class="form-label">Phone</label> <input type="text"
                                        name="phone" class="form-control" value="{{ old('phone', $patient->phone) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Birth Date</label>
                                    <input type="date" name="birthdate" class="form-control"
                                        value="{{ old('birthdate', $patient->birthdate) }}">
                                </div>
                            </div>
                        </div>
                    </div> <!-- Buttons -->
                    <div class="d-flex justify-content-end mt-4"> <a
                            href="{{ route('doctor.patients.show', $patient->id) }}" class="btn btn-light me-2"> Cancel </a>
                        <button type="submit" class="btn btn-primary"> UpdatePatient </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection