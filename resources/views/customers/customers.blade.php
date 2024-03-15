<x-app-layout>

    <div class="page-header">
        @if (auth()->user()->hasRole('admin'))
            <a href="{{ route('customers.new') }}"><button class="btn btn-primary" type="button"><i class="fa fa-plus"></i>
                    New Customer</button></a>
        @endif
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Customers</li>
            </ol>
        </nav>
    </div>
    {{-- Customers TABLE --}}
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

                        <h4 class="card-title">All Customers</h4>
                    </div>
                    <div class="col-md-8">
                        <form method="POST" action="{{route('searchCustomer')}}" class="row g-3 float-end">

                            @csrf
                            <div class="col-auto">
                                <input type="text" style="height: 40px" name="searchValue" required class="form-control" id="inputPassword2" placeholder="Search by customer name">

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
                            <th> Email </th>
                            <th> Phone Number </th>
                            <th> Created </th>
                            @if (auth()->user()->hasRole('admin'))
                                <th> Edit </th>
                            @endif
                            <th> More </th>
                        </tr>
                    </thead>
                    <tbody>

                        @if ($customers->count())
                            @foreach ($customers as $user)
                                <tr>

                                    <td> {{ $user->name }} </td>
                                    <td> {{ $user->email }} </td>
                                    <td> {{ $user->phone_number }} </td>
                                    <td> {{ $user->created_at->diffForHumans() }} </td>
                                    @if (auth()->user()->hasRole('admin'))
                                        <td> <a class="btn btn-warning"
                                                href="{{ route('customers.edit', $user->id) }}"><i
                                                    class="fa fa-edit"></i> Edit </a> </td>
                                    @endif
                                    <td> <a class="btn btn-primary" href="{{ route('customers.view', $user->id) }}">More
                                            <i class="mdi mdi-arrow-right"></i> </a> </td>

                                </tr>
                            @endforeach
                        @else
                            <div class="alert alert-danger">No records</div>
                        @endif


                    </tbody>
                </table>

                @if (!empty($customers->links))
                <div class="d-flex justify-content-center">
                    {!! $customers->links('vendor.pagination.bootstrap-5') !!}
                </div>
               @endif


            </div>
        </div>
    </div>
    {{-- END OF Customers TABLE --}}
</x-app-layout>
