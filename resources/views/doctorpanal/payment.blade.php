@extends('master')

@section('content')

<div class="container mt-4">

    <div class="card shadow-lg border-0">
        
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Payment Details</h4>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Patient Name</div>
                <div class="col-md-8">{{ $booking->patient->name }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Amount</div>
                <div class="col-md-8 text-success fw-bold">
                    {{ $booking->amount_cents }} {{ $booking->currency }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Payment Method</div>
                <div class="col-md-8">
                    <span class="badge bg-info">
                        {{ $booking->payment_method }}
                    </span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Payment Status</div>
                <div class="col-md-8">
                    @if($booking->payment_status == 'paid')
                        <span class="badge bg-success">Paid</span>
                    @else
                        <span class="badge bg-danger">Unpaid</span>
                    @endif
                </div>
            </div>

        </div>

        <div class="card-footer text-end">
            <a href="{{ route('doctorpanal') }}" class="btn btn-secondary">
                Back
            </a>
        </div>

    </div>

</div>

@endsection