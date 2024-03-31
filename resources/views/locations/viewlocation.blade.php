<x-app-layout>

    <div class="page-header">
        <h3 class="page-title"> Location Profile </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('locations') }}">Locations</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $location->location_name }}</li>
            </ol>
        </nav>
    </div>
    {{-- INFO SECTION --}}

    @if (session('feedback'))
        <div class="alert alert-success">
            {{ session('feedback') }}
        </div>
        @endif @if (session('warning_feedback'))
            <div class="alert alert-warning">
                {{ session('warning_feedback') }}
            </div>
        @endif
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if (auth()->user()->hasRole('admin'))
                        <div class="d-flex justify-content-between align-items-center">


                            <form method="POST" action="{{ route('deleteLocation', [$location->id]) }}">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger dltBtn" data-id={{ $location->id }}
                                    data-text="Are you sure you want to delete this location and their associated customers and invoices? This is irreversible."><i
                                        class="fa fa-remove"></i> Delete Location</button>
                            </form>


                        </div>
                        <hr>
                        @endif

                        <div class="row">
                            <div class="col-md-2 col-lg-2 text-center">


                                <div class="nav-profile-text">
                                    <h4 class="card-title">{{ $location->location_name }}</h4>

                                </div>
                            </div>

                            <div class="col-md-10 col-lg-10">
                                <div class="row">

                                    <div class="col-md-4 col-lg-4">
                                        <label>Manager:</label>
                                        <p><b>{{ $location->manager->name }}</b></p>
                                    </div>


                                    <div class="col-md-4 col-lg-4">
                                        <label>Created:</label>
                                        <b>{{ $location->created_at }}</b> <br>
                                    </div>


                                    <div class="col-md-4 col-lg-4">

                                        <label>Updated:</label>
                                        <b>{{ $location->updated_at->diffForHumans() }}</b>
                                    </div>
                                </div>
                            </div>
                        </div>




                </div>
            </div>
        </div>


</x-app-layout>
