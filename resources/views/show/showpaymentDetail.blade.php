@extends('master')

@section('title')
Payment Detail
@stop

@section('content')
<div class="container mt-4">
    <h4>Payment Detail for Booking #{{ $payment->booking_id }}</h4>
    <ul class="list-group">
        <li class="list-group-item"><strong>Provider:</strong> {{ $payment->provider }}</li>
        <li class="list-group-item"><strong>Status:</strong> {{ $payment->status }}</li>
        <li class="list-group-item"><strong>Amount:</strong> {{ $payment->amount_cents / 100 }} {{ strtoupper($payment->currency) }}</li>
        <li class="list-group-item"><strong>Payment ID:</strong> {{ $payment->provider_payment_id }}</li>
        <li class="list-group-item"><strong>Created At:</strong> {{ $payment->created_at }}</li>
    </ul>
    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection