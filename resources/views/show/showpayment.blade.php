@extends('master')

@section('title')

Booking
@stop

@section("content")
@if (session()->has('delete'))
    <div class="alert alert-success">
        The Booking deleted successfully.
    </div>
@endif

    <div class="col-12">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4"> payment Table</h6>
                            <div>
                                <a href="{{ route('bookingtable') }}" class="btn btn-secondary">Back</a>
 
                            </div>
                            <div class="table-responsive">
           
                                <table class="table">
                                    <thead>
                                         <tr>
            <th>#</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Booking ID</th>
            <th>Amount</th>
            <th>Currency</th>
            <th>Payment Method</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
                                        
                                    </thead>
                                    <tbody>
                                               @foreach ($payments as $payment)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $payment->booking->patient->name ?? 'N/A' }}</td>
                <td>{{ $payment->booking->doctor->name ?? 'N/A' }}</td>
                <td>{{ $payment->booking_id }}</td>
                <td>{{ number_format($payment->amount_cents / 100, 2) }}</td>
                <td>{{ strtoupper($payment->currency) }}</td>
                <td>{{ $payment->provider }}</td>
                <td>{{ $payment->status }}</td>
                <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        @endforeach
                                        <div class="d-flex justify-content-center mt-3">
    {{ $payments->links('pagination::bootstrap-5') }}
</div>
                                                                            </tbody>


                                </table>
                            </div>
                        </div>
                    </div>
    
              
@endsection

