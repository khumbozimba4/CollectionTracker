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
@if (session('feedback'))
<div class="alert alert-success">
    {{ session('feedback') }}
</div>
@endif
@if (session('warning_feedback'))
<div class="alert alert-danger">
    {{ session('warning_feedback') }}
</div>
@endif

  <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    @if (auth()->user()->hasRole('admin'))

                    <form method="POST" class="float-end" action="{{route('deleteCustomer',[$customer->id])}}">
                        @csrf
                        @method('delete')
                            <button class="btn btn-danger dltBtn" data-id={{$customer->id}}  data-text="Are you sure you want to delete this customer? This is irreversible."><i class="fa fa-remove"></i> Delete customer</button>
                    </form>
                    @endif

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

</x-app-layout>

