<x-app-layout>

    <div class="page-header">
        <h3 class="page-title"> User Profile </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('users') }}">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
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


                            <form method="POST" action="{{ route('deleteUser', [$user->id]) }}">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger dltBtn" data-id={{ $user->id }}
                                    data-text="Are you sure you want to delete this user and their associated customers and invoices? This is irreversible."><i
                                        class="fa fa-remove"></i> Delete User</button>
                            </form>

                            <p></p>
                            <a href="{{ route('users.resetpassword', $user->id) }}">
                                <button class="btn btn-primary" type="button"><i class="fa fa-key"></i> Reset
                                    Password</button>
                            </a>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-md-2 col-lg-2 text-center">
                                <div class="nav-profile-img">
                                    <img src="../../../../../../../Profile Images/user-icon.png" alt="image"
                                        width="100%">
                                </div>

                                <div class="nav-profile-text">
                                    <h4 class="card-title">{{ $user->name }}</h4>
                                    @if ($user->status === 'Active')
                                        <span class="badge badge-success"><i class="fa fa-check-circle"></i>
                                            {{ $user->status }}</span>
                                    @elseif($user->status === 'Blocked')
                                        <span class="badge badge-danger"><i class="fa fa-ban"></i>
                                            {{ $user->status }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-10 col-lg-10">
                                <div class="row">

                                    <div class="col-md-4 col-lg-4">
                                        <label>Role:</label>
                                        <p><b>{{ $user->roles->first()->display_name }}</b></p>
                                    </div>

                                    <div class="col-md-4 col-lg-4">
                                        <label>Email:</label>
                                        <p><b>{{ $user->email }}</b></p>
                                    </div>



                                    <div class="col-md-4 col-lg-4">
                                        <label>Phone Number:</label>
                                        <p><b>{{ $user->phone_number }}</b></p>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <label>Created:</label>
                                        <b>{{ $user->created_at }}</b> <br>
                                    </div>


                                    <div class="col-md-4 col-lg-4">

                                        <label>Updated:</label>
                                        <b>{{ $user->updated_at->diffForHumans() }}</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                        <div class="row">
                            <div class="col-xl-3 col-xxl-3 col-sm-6">
                                <div class="widget-stat card">
                                    <div class="card-body">
                                        <h4 class="card-title">Total Target</h4>
                                        <h3>MWK {{ $invoices['target'] }}</h3>
                                        <div class="progress mb-2">
                                            <div class="progress-bar progress-animated bg-primary"
                                                style="width: {{ $invoices['target'] }}%"></div>
                                        </div>
                                        <small>MWK {{ $invoices['target'] }}</small>
                                    </div>
                                </div>
                            </div>
                            @php
                                //dd($data["target"]);
                                if ($invoices['target'] != 0) {
                                    $percent = ($invoices['total_collected'] / $invoices['target']) * 100;
                                } else {
                                    $percent = 0;
                                }
                            @endphp
                            <div class="col-xl-3 col-xxl-3 col-sm-6">
                                <div class="widget-stat card">
                                    <div class="card-body">
                                        <h4 class="card-title">Total Collected</h4>
                                        <h3>MWK {{ $invoices['total_collected'] }}</h3>
                                        <div class="progress mb-2">
                                            <div class="progress-bar progress-animated @if ($percent >= 90) bg-success
                                       @else
                                       bg-warning @endif"
                                                style="width: {{ $invoices['total_collected'] }}%"></div>
                                        </div>
                                        <small>MWK {{ $invoices['total_collected'] }} </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-xxl-3 col-sm-6">
                                <div class="widget-stat card">
                                    <div class="card-body">
                                        <h4 class="card-title">Total Remaining</h4>
                                        <h3>MWK {{ $invoices['total_remaining'] }}</h3>
                                        <div class="progress mb-2">
                                            <div class="progress-bar progress-animated bg-red"
                                                style="width: {{ $invoices['total_remaining'] }}%"></div>
                                        </div>
                                        <small>MWK {{ $invoices['total_remaining'] }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-xxl-3 col-sm-6">
                                <div class="widget-stat card">
                                    <div class="card-body">
                                        <h4 class="card-title">Total Customers</h4>
                                        <h3>{{ $invoices['customers'] }}</h3>
                                        <div class="progress mb-2">
                                            <div class="progress-bar progress-animated bg-red"
                                                style="width: {{ $invoices['customers'] }}%"></div>
                                        </div>
                                        <small>{{ $invoices['customers'] }}</small>
                                    </div>
                                </div>
                            </div>



                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="mt-4">
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                        </div>
                    @endif


                </div>
            </div>
        </div>


        @section('scripts')
            <script>
                const ctx = document.getElementById('myChart');

                const data = {
                    labels: [
                        'Target',
                        'Collected',
                        'Remaining'
                    ],
                    datasets: [{
                        label: 'Collected vs Remaining vs Target',
                        data: [{{ $invoices['target'] }}, {{ $invoices['total_collected'] }},
                            {{ $invoices['total_remaining'] }}
                        ],
                        backgroundColor: [
                            'rgb(255, 205, 86)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 99, 132)'
                        ],
                        hoverOffset: 4
                    }]
                };

                new Chart(ctx, {
                    type: 'doughnut',
                    data: data,
                });
            </script>
        @endsection
</x-app-layout>
