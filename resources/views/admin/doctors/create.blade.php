@extends('master')

@section('title', 'add new doctor')

@section("content")

    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h3>Add Doctor</h3>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.doctors.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control">
            </div>

            <div class="mb-3">
                <label>Bio</label>
                <input type="text" name="bio" class="form-control">
            </div>

            <div class="mb-3">
                <label>License Number</label>
                <input type="text" name="license_number" class="form-control">
            </div>

            <div class="mb-3">
                <label>Years Of Experience</label>
                <input type="number" name="years_of_experience" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">
                Create Doctor
            </button>

        </form>

    </div>

@endsection

