@extends('master')


@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="d-flex justify-content-between mb-4">
            <h5 class="mb-0">Patient Details</h5>
            <a href="{{ route('doctor.patients.index') }}" class="btn btn-sm btn-primary">Back</a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <!-- Header -->
            <div class="p-4 d-flex align-items-center justify-content-between bg-light">
                <div class="d-flex align-items-center">
                    <!-- Avatar -->
                    <img src="https://static.vecteezy.com/system/resources/thumbnails/020/416/057/small/man-icon-vector.jpg"
                        class="rounded-circle border" alt="{{ $patient->name }}"
                        style="width:110px;height:110px;object-fit:cover;">

                    <!-- Name -->
                    <div class="ms-4">
                        <h4 class="fw-bold mb-1">{{ $patient->name }}</h4>

                        <div class="text-muted small">
                            Patient Profile
                        </div>

                        <span class="badge bg-primary-subtle text-primary mt-2">
                            Age: {{ $patient->age }}
                        </span>
                    </div>

                </div>

                <div>
                    <button class="btn btn-outline-primary btn-sm me-2">
                        <a href="{{ route('doctor.patients.edit', $patient->id) }}">Edit</a>
                    </button>
                    <button class="btn btn-primary btn-sm">
                        Book Appointment
                    </button>
                </div>

            </div>

            <div class="p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="small text-muted mb-1">
                            Email Address
                        </div>
                        <div class="fw-semibold">
                            <i class="bi bi-envelope me-2 text-primary"></i>
                            {{ $patient->email }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="small text-muted mb-1">
                            Phone Number
                        </div>
                        <div class="fw-semibold">
                            <i class="bi bi-telephone me-2 text-primary"></i>
                            {{ $patient->phone }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mt-4">

            <div class="card-body">

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4" id="patientTabs" role="tablist">

                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#bookings" type="button">
                            Bookings
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#medicalHistory" type="button">
                            Medical History
                        </button>
                    </li>

                </ul>

                <!-- Tab Content -->
                <div class="tab-content">

                    <!-- BOOKINGS TAB -->
                    <div class="tab-pane fade show active" id="bookings">

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead class="text-muted small">
                                    <tr>
                                        <th>Doctor</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($patient->bookings as $booking)

                                        <tr>

                                            <td>
                                                Dr. {{ $booking->doctor->user->name }}
                                            </td>

                                            <td>
                                                {{ $booking->starts_at_utc }}
                                            </td>

                                            <td>
                                                {{ $booking->ends_at_utc }}
                                            </td>

                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $booking->status }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $booking->payment_status }}
                                                </span>
                                            </td>

                                            <td>
                                                {{ $booking->amount_cents / 100 }}
                                                {{ $booking->currency }}
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>


                    <!-- MEDICAL HISTORY TAB -->
                    <div class="tab-pane fade" id="medicalHistory">

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead class="text-muted small">
                                    <tr>
                                        <th>Doctor</th>
                                        <th>Diagnosis</th>
                                        <th>Notes</th>
                                        <th>Recommendations</th>
                                        <th>Follow Up</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($patient->medicalRecords as $record)

                                        <tr>

                                            <td>
                                                Dr. {{ $record->doctor->name }}
                                            </td>

                                            <td>
                                                {{ Str::limit($record->diagnosis, 50) }}
                                            </td>

                                            <td>
                                                {{ Str::limit($record->notes, 50) }}
                                            </td>

                                            <td>
                                                {{ Str::limit($record->recommendations, 50) }}
                                            </td>

                                            <td>

                                                @if($record->follow_up_required)

                                                    <span class="badge bg-warning text-dark">
                                                        After {{ $record->follow_up_after_days }} days
                                                    </span>

                                                @else

                                                    <span class="badge bg-success">
                                                        No
                                                    </span>

                                                @endif
                                            </td>
                                            <td>
                                                {{ $record->created_at->format('Y-m-d') }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection