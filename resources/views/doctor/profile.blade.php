@extends('master')

@section('title','Doctor Profile')

@section('content')

    <div class="container">

        <h3>My Profile</h3>

        <div class="card mb-4 p-3">

            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Phone:</strong> {{ $user->phone }}</p>
            <p><strong>Bio:</strong> {{ $doctor->bio }}</p>
            <p><strong>Years Of Experience:</strong> {{ $doctor->years_of_experience }} years</p>

        </div>

        <h4>Change Password</h4>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <a href="#" onclick="togglePasswordForm()" class="btn btn-primary">
            Change Password
        </a>

        <div id="passwordForm" style="display:none; margin-top:20px;">
            <form method="POST" action="{{ route('doctor.change.password') }}">
                @csrf

                <div class="mb-3">
                    <label>Current Password</label>
                    <input type="password" name="current_password" class="form-control">
                </div>

                <div class="mb-3">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="new_password_confirmation" class="form-control">
                </div>

                <button class="btn btn-success">Update Password</button>
            </form>
        </div>

    </div>
        <script>
            function togglePasswordForm() {
                var form = document.getElementById("passwordForm");

                if (form.style.display === "none") {
                    form.style.display = "block";
                } else {
                    form.style.display = "none";
                }
            }
        </script
@endsection
