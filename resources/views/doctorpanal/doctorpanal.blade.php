@extends('master')

@section('title','Doctor Panel')

@section('content')
@if (session()->has('cancel'))
    <div class="alert alert-success">
{{ session(key: 'cancel') }}
    </div>
@endif
@if (session()->has('update'))
    <div class="alert alert-success">
  {{ session('update') }}
    </div>
@endif

<div class="col-12">
    <div class="bg-light rounded h-100 p-4">
        <h6 class="mb-4">My Bookings</h6>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Clinic</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Status</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Operations</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $booking->patient->name }}</td>
                        <td>{{ $booking->clinic->name ?? 'N/A' }}</td>
                        <td>{{ $booking->starts_at_utc }}</td>
                        <td>{{ $booking->ends_at_utc }}</td>
                        <td>{{ $booking->status }}</td>
                        <td>{{ $booking->payment_method }}</td>
                        <td>{{ $booking->payment_status }}</td>
                        <td>
                            <a href="{{ route('doctor.edit', $booking->id) }}" class="btn btn-info btn-sm"><i class="fa-solid fa-pen"></i></a>
                            <a href="{{ route('doctor.cancel', $booking->id) }}" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                            <a href="{{ route('doctor.payment', $booking->id) }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-baht-sign"></i></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination links -->
            <div>
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection