@extends('master')

@section('title', 'reports ')

@section("content")
   <div class="col-xl-6">
    <h4>Doctor Booking Trport</h4>
    <select class="form-control" name="doctor" id="doctorSelect">
        <option value="all">All Doctors</option>
        @foreach ($Doctors as $doctor)
            <option value="{{ $doctor->id }}">{{ $doctor->user->name }}</option>
        @endforeach
    </select>
</div>

<h4 id="doctorName" style="margin-top: 20px; text-align:center; margin-right: 10px;">All Doctors</h4>
<div class="col-xl-11">
    <table class="table table-bordered table-striped" style="margin-top: 20px; text-align: center; margin-right: 80px; ">
        <thead>
            <tr>
                <th>#</th>
            
                <th id="doctor">Doctor Name</th>
                <th>Patient Name</th>
                <th>Clinic</th>
                <th>starts at</th>
                <th>end at</th>
                <th>payments method</th>
                <th>payment status</th>
                <th>amount </th>
                <th>currency </th>
            </tr>
        </thead>
        <tbody id="reviewsBody">
            @foreach ($Doctors as $doctor)
                @foreach ($doctor->bookings as $index => $booking)
                   
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $doctor->user->name }}</td>
                        <td>{{ $booking->patient->name ?? 'N/A' }}</td>
                        <td>{{ $booking->timeSlot->clinic->name ?? 'N/A' }}</td>
                        <td>{{ $booking->starts_at_utc ?? 'N/A' }}</td>
                        <td>{{ $booking->ends_at_utc ?? 'N/A' }}</td>
                        <td>{{ $booking->payment_method ?? 'N/A' }}</td>
                        <td>{{ $booking->payment_status ?? 'N/A' }}</td>
                        <td>{{ $booking->amount_cents ?? 'N/A' }}</td>
                        <td>{{ $booking->currency ?? 'N/A' }}</td>
                        
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>


    
@endsection


@section("js")

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#doctorSelect').on('change', function() {
    var doctorId = $(this).val();
    
     if (doctorId != "all") {
        // اخفاء عمود الدكتور
        $('#doctor').hide();
        
    } else {
        // اظهار عمود الدكتور
        $('#doctor').show();
        
    }
    
    $.ajax({
        url: '/admin/doctor-booking-report/doctors',
        type: 'GET',
        data: { doctor_id: doctorId },
        success: function(data) {
          var html = '';

          

               $.each(data, function(i, doctor) {
                    
                    
                         if(data.isEmpty == false){
                                   html = '<tr><td colspan="9">No bookings yet.</td></tr>';
                              } else {
                                    $.each(doctor.bookings, function(index, booking) {
                                   
                                        html += '<tr>' +
                                             '<td>' + (index + 1) + '</td>' +
                                             
                                             (booking.doctor? '<td>'+ booking.doctor.user.name +'</td>' : '') +

                                             '<td>' + booking.patient.name + '</td>' +
                                             '<td>' + booking.time_slot.clinic.name + '</td>' +
                                             '<td>' + booking.starts_at_utc + '</td>' +
                                             '<td>' + booking.ends_at_utc + '</td>' +
                                             '<td>' + booking.payment_method + '</td>' +
                                             '<td>' + booking.payment_status + '</td>' +
                                             '<td>' + booking.amount_cents + '</td>' +
                                             '<td>' + booking.currency + '</td>' +
                                        '</tr>';

                                   });
                              }


                        
               

               

            
            $('#reviewsBody').html(html);

            // Update doctor name heading
            var selectedDoctor = $('#doctorSelect option:selected').text();
            $('#doctorName').text(selectedDoctor);
        })},
        
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});
</script>
     

@endsection


