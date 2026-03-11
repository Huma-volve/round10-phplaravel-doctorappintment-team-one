<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="sidebar pe-4 pb-3">
<nav class="navbar bg-light navbar-light">
          <a href="index.html" class="navbar-brand mx-4 mb-3">
          <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>DASHMIN</h3>
          </a>
          <div class="d-flex align-items-center ms-4 mb-4">
          <div class="position-relative">
               <img class="rounded-circle" src=" {{asset('dashboard/img/user.jpg')}}" alt="" style="width: 40px; height: 40px;">
               <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
          </div>
          <div class="ms-3">
               <h6 class="mb-0">Jhon Doe</h6>
               <span>Admin</span>
          </div>
          </div>
          <div class="navbar-nav w-100">
          <a href="index.html" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
          @if(auth()->check() && auth()->user()->role=='admin')
          <div class="nav-item dropdown">
               <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-users"></i></i>Users</a>
               <div class="dropdown-menu bg-transparent border-0">
                    <a href="{{ route('showpatient') }}" class="dropdown-item">patient</a>
                    <a href="{{ route('showdoctor') }}" class="dropdown-item">Doctor</a>
               </div>
          </div>
         
          <a href="{{ route('bookingtable') }}" class="nav-item nav-link"><i class="fa-solid fa-table"></i>Booking</a>
         
          
         
          @endif
            @if(auth()->check() && auth()->user()->role=='doctor')
          <a href="{{ route('doctorpanal') }}" class="nav-item nav-link"><i class="fa-solid fa-table"></i>mybooking</a>
           @endif
              @if(auth()->check() && auth()->user()->role=='admin')
               <a href="{{ route('admin.specialties.index') }}" class="nav-item nav-link"><i class="fa-solid fa-bars"></i>Specialties</a>
          @endif
         
          
            <div class="navbar-nav w-100">
                @if (auth()->check() && auth()->user()->role === 'admin')
                  
                 
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                                class="fa fa-laptop me-2"></i>FAQS & Policies</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="/admin/Faqs" class="dropdown-item">FAQS</a>
                            <a href="/admin/Policies" class="dropdown-item">Policies</a>

                        </div>

                         <div class="nav-item dropdown">
                              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-chart-bar me-2"></i></i>Reports</a>
                              <div class="dropdown-menu bg-transparent border-0">
                                   <a href="/admin/doctor-reviews-report" class="dropdown-item">Doctor Review Report</a>
                                   <a href="/admin/doctor-booking-report" class="dropdown-item">Doctor Booking Report</a>
                              
                              </div>
                         </div>
                    </div>
                @endif
            </div>
        </div>
    </nav>
</div>
