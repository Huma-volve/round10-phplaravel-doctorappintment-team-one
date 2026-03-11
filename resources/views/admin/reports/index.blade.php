@extends('master')

@section('title', 'reports ')

@section("content")
   <div class="col-xl-6">
    <h4> Reviews Doctors Report </h4>
    <select class="form-control" name="doctor" id="doctorSelect">
        <option value="all">All Doctors</option>
        @foreach ($Doctors as $doctor)
            <option value="{{ $doctor->id }}">{{ $doctor->user->name }}</option>
        @endforeach
    </select>
</div>

<h4 id="doctorName" style="margin-top: 20px; text-align:center; margin-right: 10px;">All Doctors</h4>
<div class="col-xl-10">
    <table class="table table-bordered table-striped" style="margin-top: 20px; text-align: center; margin-right: 80px; ">
        <thead>
            <tr>
                <th>#</th>
            
                <th id="doctor">Doctor Name</th>
                <th>Patient Name</th>
                <th>Comment</th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody id="reviewsBody">
            @foreach ($Doctors as $doctor)
                @foreach ($doctor->reviews as $index => $review)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $doctor->user->name }}</td>
                        <td>{{ $review->patient->name ?? 'N/A' }}</td>
                        <td>{{ $review->comment }}</td>
                        <td>{{ $review->rating }}</td>
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
        url: '/admin/doctor-reviews-report/doctors',
        type: 'GET',
        data: { doctor_id: doctorId },
        success: function(data) {
            var html = '';
            if(data.length === 0){
                html = '<tr><td colspan="4">No reviews yet.</td></tr>';
            } else {
                $.each(data, function(index, review) {
                    html += '<tr>' +
                             
                            '<td>' + (index + 1) + '</td>' ;

                    if(review.doctor_name && review.doctor_name){
                         html += '<td>' + review.doctor_name + '</td>';
                    }

                     html += 
                            
                         
                            '<td>' + review.patient.name + '</td>' +
                            '<td>' + review.comment + '</td>' +
                            '<td>' + review.rating + '</td>' +
                            '</tr>';
                });
            }
            $('#reviewsBody').html(html);

            // Update doctor name heading
            var selectedDoctor = $('#doctorSelect option:selected').text();
            $('#doctorName').text(selectedDoctor);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});
</script>
     

@endsection


