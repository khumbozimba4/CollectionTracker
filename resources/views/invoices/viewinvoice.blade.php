<x-app-layout>

<div class="page-header">
<h3 class="page-title"> Invoice Profile </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('invoices')}}">Invoices</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$invoice->name}}</li>
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
                                <h4 class="card-title">{{$invoice->name}}</h4>
                           
                            </div>
                        </div>

                        <div class="col-md-10 col-lg-10">
                                     <div class="row">

                                        <div class="col-md-4 col-lg-4">
                                            <label>Sales Person:</label>
                                            <p><b>{{$invoice->user->name}}</b></p>
                                        </div>

                                        <div class="col-md-4 col-lg-4">
                                            <label>Customer:</label>
                                            <p><b>{{$invoice->customer->name}}</b></p>
                                        </div>

                                        <div class="col-md-4 col-lg-4">
                                            <label>Created:</label>
                                            <b>{{$invoice->created_at}}</b> <br>

                                            <label>Updated:</label>
                                            <b>{{$invoice->updated_at->diffForHumans()}}</b>
                                        </div>
                                    </div>
                        </div>
                  </div>





                  </div>
                </div>
              </div>
{{-- END OF INFO SECTION --}}

{{-- Invoices TABLE --}}


{{-- END OF Invoices TABLE --}}
</x-app-layout>

