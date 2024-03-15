<x-app-layout>

    <div class="page-header">
        <a href="{{ route('locations.new') }}"><button class="btn btn-primary" type="button"><i class="fa fa-plus"></i> New
                Location</button></a>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Locations</li>
            </ol>
        </nav>
    </div>

    @if (session('feedback'))
        <div class="alert alert-success">
            {{ session('feedback') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body" style="width: 100%; overflow-x: auto;">
                <div class="row">
                    <div class="col-md-4">

                        <h4 class="card-title">All Locations</h4>
                    </div>
                    <div class="col-md-8">
                        <form method="POST" action="{{route('searchLocation')}}" class="row g-3 float-end">

                            @csrf
                            <div class="col-auto">
                                <input type="email" style="height: 40px" name="searchValue" required class="form-control" id="inputPassword2" placeholder="Search by location email">

                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary mb-3">Search</button>
                            </div>
                        </form>
                    </div>
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th> Name </th>
                            <th> Manager </th>
                            <th> Created </th>
                            <th> Edit </th>
                            <th> More </th>
                        </tr>
                    </thead>
                    <tbody>

                        @if ($locations->count())
                            @foreach ($locations as $location)

                                    <tr>

                                        <td> {{ $location->location_name }} </td>
                                        <td> {{ $location->user->name }} </td>

                                        <td> {{ $location->created_at->diffForHumans() }} </td>

                                        <td> <a class="btn btn-warning" href="{{ route('locations.edit', $location->id) }}"><i
                                                    class="fa fa-edit"></i> Edit </a> </td>

                                        <td> <a class="btn btn-primary" href="{{ route('locations.view', $location->id) }}">More <i
                                                    class="mdi mdi-arrow-right"></i> </a> </td>



                                    </tr>

                            @endforeach
                        @else
                            <div class="alert alert-danger">No records</div>
                        @endif


                    </tbody>
                </table>



                @if (!empty($locations->links))
                <div class="d-flex justify-content-center">
                    {!! $locations->links('vendor.pagination.bootstrap-5') !!}
                </div>
               @endif

            </div>
        </div>
    </div>
    {{-- END OF locations TABLE --}}
</x-app-layout>
