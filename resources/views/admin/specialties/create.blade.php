@extends('master')

@section('content')

    <div class="container">

        <h3>Add Specialty</h3>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.specialties.store') }}">

            @csrf

            <div class="mb-3">
                <label>Specialty Name</label>
                <input type="text" name="name" class="form-control">
            </div>

            <button class="btn btn-primary">
                Save
            </button>

        </form>

    </div>

@endsection
