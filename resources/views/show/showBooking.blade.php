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
                            <h6 class="mb-4"> Booking Table</h6>
                            <div>
                                  <a href="{{ route('paymenttable') }}"
     style="
       display: inline-block;
       padding: 10px 20px;
       background-color: #007BFF;
       color: white;
       text-decoration: none;
       border-radius: 5px;
       font-weight: bold;
       font-family: Arial, sans-serif;
     "
   
  >
 payment table
  </a>
                            </div>
                            <div class="table-responsive">
           
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Patient</th>
                                            <th scope="col"> Doctor</th>
                                            <th scope="col">starts_at_utc</th>
                                            <th scope="col">ends_at_utc</th>
                                            <th scope="col">clinic</th>
                                            <th scope="col">status</th>
                                            <th scope="col">payment_method</th>
                                            <th scope="col">payment_status</th>
                                            <th scope="col">amount_cents</th>
                                            <th scope="col">currency</th>
                                       
                                            <th scope="col">operation</th>
                                            
                                        </tr>
                                        
                                    </thead>
                                    <tbody>
                                        @foreach ($booking as $B)
                                            
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                          <td>{{ $B->patient->name }}</td>

                                          <td>{{ $B->doctor->user->name }}</td>

                                          <td>{{ $B->starts_at_utc }}</td>

                                          <td>{{ $B->ends_at_utc }}</td>

                                          <td>{{ $B->clinic->name ?? 'N/A' }}</td>

                                          <td>{{ $B->status }}</td>

                                          <td>{{ $B->payment_method }}</td>

                                          <td>{{ $B->payment_status }}</td>

                                          <td>{{ $B->amount_cents }} $</td>

                                          <td>{{ $B->currency }}</td>

                                               

                                        
                                                    <td>
                                                 <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                      data-bs-toggle="modal"
                                                  data-bs-target="#delete{{$B->id}}"

                                                        title="delete"><i class="las la-trash"></i></a>
                           
    <a href="{{ route('showPayment', $B->id) }}" class="btn btn-info btn-sm">
      <i class="fa-solid fa-baht-sign"></i>
    </a>

                                                    </td>
                                        </tr>
                                                                                
                                        @endforeach
                                        <div class="d-flex justify-content-center mt-3">
    {{ $booking->links('pagination::bootstrap-5') }}
</div>
                                                                            </tbody>

@foreach ($booking as $B)
    
                                            <div class="modal" id="delete{{$B->id}}">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title">Delete Booking </h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    <span aria-hidden="true">&times;</span>
</button>
                                </div>
                                <form action="{{ route('deleteBooking',$B->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-body">
                                        <p> Do you sure Delete the Booking</p><br>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">close</button>
                                        <button type="submit" class="btn btn-danger">delete</button>
                                    </div>
                            </div>
                            </form>
	
                        </div>
                    </div>

                              @endforeach
       


                                </table>
                            </div>
                        </div>
                    </div>
    
              
@endsection

