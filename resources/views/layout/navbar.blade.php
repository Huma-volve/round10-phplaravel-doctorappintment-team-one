<nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
     <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
          <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
     </a>
     <a href="#" class="sidebar-toggler flex-shrink-0">
          <i class="fa fa-bars"></i>
     </a>
     <form class="d-none d-md-flex ms-4">
          <input class="form-control border-0" type="search" placeholder="Search">
     </form>
     <div class="navbar-nav align-items-center ms-auto">
     <div class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
               <i class="fa fa-envelope me-lg-2"></i>
               <span class="d-none d-lg-inline-flex">Message</span>
          </a>
          <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
               <a href="#" class="dropdown-item">
                    <div class="d-flex align-items-center">
                         <img class="rounded-circle" src=" {{asset('dashboard/img/user.jpg')}}" alt="" style="width: 40px; height: 40px;">
                         <div class="ms-2">
                         <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                         <small>15 minutes ago</small>
                         </div>
                    </div>
               </a>
               <hr class="dropdown-divider">
               <a href="#" class="dropdown-item">
                    <div class="d-flex align-items-center">
                         <img class="rounded-circle" src="{{asset('dashboard/img/user.jpg')}}" alt="" style="width: 40px; height: 40px;">
                         <div class="ms-2">
                         <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                         <small>15 minutes ago</small>
                         </div>
                    </div>
               </a>
               <hr class="dropdown-divider">
               <a href="#" class="dropdown-item">
                    <div class="d-flex align-items-center">
                         <img class="rounded-circle" src="{asset('dashboard/img/user.jpg')}}" alt="" style="width: 40px; height: 40px;">
                         <div class="ms-2">
                         <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                         <small>15 minutes ago</small>
                         </div>
                    </div>
               </a>
               <hr class="dropdown-divider">
               <a href="#" class="dropdown-item text-center">See all message</a>
          </div>
     </div>
     <div class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown">
               <i class="fa fa-bell me-lg-2"></i>
               <span class="d-none d-lg-inline-flex">Notificatin</span>
               @if($unreadCount > 0)
                    <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
               @endif
          </a>
          <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0" style="width: 400px; max-height: 400px; overflow-y: auto;">
            
               @forelse($notifications ?? [] as $notification)

              
                    <a href="" class="dropdown-item {{ is_null($notification->read_at_utc) ? 'bg-light' : '' }}">
                         <h6 class="fw-normal mb-0">{{ $notification->title ?? $notification->type }}</h6>
                         <small>{{ $notification->message ?? '' }}</small>
                         <div class="mt-1">
                              <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                         </div>
                    </a>
                    <hr class="dropdown-divider">
               @empty
               
                    <a href="#" class="dropdown-item text-center text-muted">
                         <small>No notifications</small>
                    </a>
                    <hr class="dropdown-divider">
               @endforelse
               <a href="{{ route('admin.notifications.index') }}" class="dropdown-item text-center">See all notifications</a>
          </div>
     </div>
     <div class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
               <img class="rounded-circle me-lg-2" src="{{asset('dashboard/img/user.jpg')}}" alt="" style="width: 40px; height: 40px;">
               <span class="d-none d-lg-inline-flex">John Doe</span>
          </a>
          <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
              @auth
                  @if(auth()->user()->role === 'doctor')
                      <a href="{{ route('doctor.profile') }}" class="dropdown-item">
                          My Profile
                      </a>
                  @endif
              @endauth
               <a href="#" class="dropdown-item">Settings</a>
              <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button class="dropdown-item">
                      Logout
                  </button>
              </form>
          </div>
     </div>
     </div>
</nav>
