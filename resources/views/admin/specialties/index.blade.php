@extends('master')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between mb-3">
            <h3>Specialties</h3>

            <a href="{{ route('admin.specialties.create') }}" class="btn btn-success">
                Add Specialty
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table">

            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
            </tr>
            </thead>

            <tbody>

            @foreach($specialties as $specialty)

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $specialty->name }}</td>
                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

@endsection
