<x-app-layout>

<div class="page-header">
<h3 class="page-title"> Customer Profile </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('customers')}}">Customers</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$customer->name}}</li>
        </ol>
    </nav>
</div>
{{-- INFO SECTION --}}


  <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">

                  

                  <div class="row">
                        <div class="col-md-2 col-lg-2 text-center">
                           
                            <div class="nav-profile-text">
                                <h4 class="card-title">{{$customer->name}}</h4>
                           
                            </div>
                        </div>

                        <div class="col-md-10 col-lg-10">
                                     <div class="row">

                                        <div class="col-md-4 col-lg-4">
                                            <label>Sales Person:</label>
                                            <p><b>{{$customer->user->name}}</b></p>
                                        </div>

                                        <div class="col-md-4 col-lg-4">
                                            <label>Email:</label>
                                            <p><b>{{$customer->email}}</b></p>
                                        </div>



                                        <div class="col-md-4 col-lg-4">
                                            <label>Phone Number:</label>
                                            <p><b>{{$customer->phone_number}}</b></p>
                                        </div>

                                        <div class="col-md-4 col-lg-4">
                                            <label>Created:</label>
                                            <b>{{$customer->created_at}}</b> <br>

                                            <label>Updated:</label>
                                            <b>{{$customer->updated_at->diffForHumans()}}</b>
                                        </div>
                                    </div>
                        </div>
                  </div>





                  </div>
                </div>
              </div>
{{-- END OF INFO SECTION --}}

{{-- Customers TABLE --}}


{{-- END OF Customers TABLE --}}
</x-app-layout>

