@extends('master')
@section('title',content: ' Edit Booking')
@section('content')
<h3>Edit Booking</h3>

<form action="{{ route('doctorpanel.updateBooking', $booking->id) }}" method="POST">
    @csrf
    @method('PUT')
 <label>Patient Name</label>
<input type="text" value="{{ $booking->patient->name }}" class="form-control" readonly>

<label>Patient Phone</label>
<input type="text" value="{{ $booking->patient->phone }}" class="form-control" readonly>

<label>Patient Email</label>
<input type="text" value="{{ $booking->patient->email }}" class="form-control" readonly>

    <label>Start Time</label>
    <input type="datetime-local" name="starts_at_utc" value="{{ $booking->starts_at_utc }}">

    <label>End Time</label>
    <input type="datetime-local" name="ends_at_utc" value="{{ $booking->ends_at_utc }}">

    <label>Status</label>
    <select name="status">
        <option value="pending_payment" {{ $booking->status=='pending_payment'?'selected':'' }}>Pending Payment</option>
        <option value="confirmed" {{ $booking->status=='confirmed'?'selected':'' }}>Confirmed</option>
        <option value="completed" {{ $booking->status=='completed'?'selected':'' }}>Completed</option>
        <option value="cancelled_by_patient" {{ $booking->status=='cancelled_by_patient'?'selected':'' }}>Cancelled by Patient</option>
        <option value="cancelled_by_doctor" {{ $booking->status=='cancelled_by_doctor'?'selected':'' }}>Cancelled by Doctor</option>
        <option value="rescheduled" {{ $booking->status=='rescheduled'?'selected':'' }}>Rescheduled</option>
    </select>

    <button type="submit">Update Booking</button>
</form>
@endsection