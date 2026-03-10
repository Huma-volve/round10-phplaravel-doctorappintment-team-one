@extends('master')

@section('content')

    <div class="container-fluid pt-4 px-4">

        {{-- Earnings Summary --}}
        <div class="row g-4 mb-4">

            {{-- Paid --}}
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">

                    <div>
                        <p class="mb-1 text-muted">Paid</p>

                        <h5 class="mb-0 text-success">
                            {{ number_format((int) $earningSummary->paid_amount_cents / 100, 2) }}
                        </h5>

                        <small class="text-muted">
                            {{ $earningSummary->paid_count }} bookings
                        </small>
                    </div>

                    <i class="fa fa-money-bill fa-2x text-success"></i>

                </div>
            </div>


            {{-- Unpaid --}}
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">

                    <div>
                        <p class="mb-1 text-muted">Unpaid</p>

                        <h5 class="mb-0 text-warning">
                            {{ number_format((int) $earningSummary->unpaid_amount_cents / 100, 2) }}
                        </h5>

                        <small class="text-muted">
                            {{ $earningSummary->unpaid_count }} bookings
                        </small>
                    </div>

                    <i class="fa fa-clock fa-2x text-warning"></i>

                </div>
            </div>


            {{-- Refunded --}}
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">

                    <div>
                        <p class="mb-1 text-muted">Refunded</p>

                        <h5 class="mb-0 text-danger">
                            {{ number_format((int) $earningSummary->refunded_amount_cents / 100, 2) }}
                        </h5>

                        <small class="text-muted">
                            {{ $earningSummary->refunded_count }} bookings
                        </small>
                    </div>

                    <i class="fa fa-undo fa-2x text-danger"></i>

                </div>
            </div>


            {{-- Partially Refunded --}}
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">

                    <div>
                        <p class="mb-1 text-muted">Partially Refunded</p>

                        <h5 class="mb-0 text-info">
                            {{ number_format((int) $earningSummary->partially_refunded_amount_cents / 100, 2) }}
                        </h5>

                        <small class="text-muted">
                            {{ $earningSummary->partially_refunded_count }} bookings
                        </small>
                    </div>

                    <i class="fa fa-percent fa-2x text-info"></i>

                </div>
            </div>

        </div>



        {{-- Bookings Table --}}
        <div class="bg-light rounded h-100 p-4">

            <h6 class="mb-4">Bookings</h6>

            <table class="table table-hover">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Booking Status</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($bookings as $booking)

                        <tr>

                            <td>{{ $booking->id }}</td>

                            <td>{{ $booking->patient->name ?? '-' }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($booking->starts_at_utc)->format('Y-m-d H:i') }}
                            </td>

                            <td>
                                {{ number_format($booking->amount_cents / 100, 2) }}
                                {{ $booking->currency }}
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $booking->payment_method }}
                                </span>
                            </td>


                            {{-- Payment Status --}}
                            <td>

                                @php
                                    $paymentColors = [
                                        'paid' => 'success',
                                        'unpaid' => 'secondary',
                                        'failed' => 'danger',
                                        'refunded' => 'danger',
                                        'partially_refunded' => 'warning'
                                    ];
                                @endphp

                                <span class="badge bg-{{ $paymentColors[$booking->payment_status] ?? 'secondary' }}">
                                    {{ $booking->payment_status }}
                                </span>

                            </td>


                            {{-- Booking Status --}}
                            <td>

                                @php
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'pending_payment' => 'warning',
                                        'confirmed' => 'success',
                                        'completed' => 'primary',
                                        'cancelled' => 'danger'
                                    ];
                                @endphp

                                <span class="badge bg-{{ $statusColors[$booking->status] ?? 'secondary' }}">
                                    {{ $booking->status }}
                                </span>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

            <div class="d-flex justify-content-center mt-4">
                {{ $bookings->links() }}
            </div>

        </div>

    </div>

@endsection