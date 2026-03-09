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
            <p><strong>Specialties:</strong>
                @if($doctor->specialties->isEmpty())
                    Not assigned
                @else
                    {{ $doctor->specialties->pluck('name')->join(', ') }}
                @endif
            </p>
        </div>

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

        <a href="#" onclick="togglePasswordForm()" class="btn btn-primary mb-3">
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
        <!-- Button to toggle specialties form -->
        <a href="#" onclick="toggleSpecialtyForm()" class="btn btn-primary mb-3">
            Edit Specialties
        </a>

        <!-- Hidden form -->
        <div id="specialtyForm" style="display:none; margin-top:15px;">
            <form method="POST" action="{{ route('doctor.profile.update.specialties') }}">
                @csrf

                <div class="mb-3">
                    <label>Edit Specialties</label>
                    <select name="specialties[]" class="form-control" multiple>
                        @foreach(\App\Models\Specialty::all() as $specialty)
                            <option value="{{ $specialty->id }}"
                                    @if($doctor->specialties->contains($specialty->id)) selected @endif>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple specialties</small>
                </div>

                <button class="btn btn-success">Update Specialties</button>
            </form>
        </div>
        <!-- Button to toggle profile edit form -->
        <a href="#" onclick="toggleProfileForm()" class="btn btn-primary mb-3">
            Edit Profile
        </a>

        <!-- Hidden form -->
        <div id="profileForm" style="display:none; margin-top:15px;">
            <form method="POST" action="{{ route('doctor.profile.update') }}">
                @csrf

                <div class="mb-3">
                    <label>Bio</label>
                    <textarea name="bio" class="form-control" rows="3">{{ $doctor->bio }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Years Of Experience</label>
                    <input type="number" name="years_of_experience" class="form-control" value="{{ $doctor->years_of_experience }}">
                </div>

                <button class="btn btn-success">Update Profile</button>
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

            function toggleSpecialtyForm() {
                var form = document.getElementById("specialtyForm");

                if (form.style.display === "none") {
                    form.style.display = "block";
                } else {
                    form.style.display = "none";
                }
            }
            function toggleProfileForm() {
                var form = document.getElementById("profileForm");
                if (form.style.display === "none") {
                    form.style.display = "block";
                } else {
                    form.style.display = "none";
                }
            }
        </script
@endsection
