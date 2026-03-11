@extends('master')

@section('title', 'dashboard')

@section("content")

     <div class="container-fluid pt-4 px-4">
          <div class="row g-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                          <i class="fa fa-user-injured fa-3x text-primary"></i>
                         <div class="ms-3">
                              <p class="mb-2">Patient</p>
                              <h6 class="mb-0">{{ $patients }}</h6>
                         </div>
                    </div>
               </div>
               <div class="col-sm-6 col-xl-3">
                    <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                         <i class="fa fa-user-md fa-3x text-primary"></i>
                         <div class="ms-3">
                              <p class="mb-2">Doctors</p>
                              <h6 class="mb-0">{{ $Doctors }}</h6>
                         </div>
                    </div>
               </div>
              
               <div class="col-sm-6 col-xl-3">
                    <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                         <i class="fa fa-calendar-check fa-3x text-primary"></i>
                         <div class="ms-3">
                              <p class="mb-2">Booking</p>
                              <h6 class="mb-0">{{ $Booking }}</h6>
                         </div>
                    </div>
               </div>
               <div class="col-sm-6 col-xl-3">
                    <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                         <i class="fa fa-comments fa-3x text-primary"></i>
                         <div class="ms-3">
                              <p class="mb-2">Conversations</p>
                              <h6 class="mb-0">{{ $conversations }}</h6>
                         </div>
                    </div>
               </div>
          </div>
     </div>

     <div class="container-fluid pt-4 px-4">
          <div class="row g-4">
               <div class="col-sm-12 col-xl-12" >
                    <div class="bg-light text-center rounded p-4">
                         <div class="d-flex align-items-center justify-content-between mb-4">
                              <h6 class="mb-0">Doctors' appointments</h6>
                              <a href="">Show All</a>
                         </div>
                         <canvas id="Doctors"></canvas>
                    </div>
               </div>
               
          
          </div>
     </div>


@endsection

@section("js")

<script>
     var labels = @json($labelBooking   );
     var data = @json($dataBooking);

     var ctx = document.getElementById('Doctors').getContext('2d');

     new Chart(ctx, {
     type: 'line',
     data: {
          labels: labels,
          datasets: [
               {
                    label: 'Daily Appointments',
                    data: data,
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0,123,255,0.2)',
                    borderWidth: 2
               }
          ]
     }
     });
</script>

@endsection



