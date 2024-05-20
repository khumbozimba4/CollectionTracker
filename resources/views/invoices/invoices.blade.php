<x-app-layout>

    <div class="page-header">
        @if (auth()->user()->hasRole('admin'))
            <a href="{{ route('invoices.new') }}"><button class="btn btn-primary" type="button"><i class="fa fa-plus"></i>
                    New Invoice</button></a>
        @endif
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Invoices</li>
            </ol>
        </nav>
    </div>


    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body" style="width: 100%; overflow-x: auto;">
                <div class="row">
                    <div class="col-md-4">

                        <h4 class="card-title">All Invoices</h4>
                    </div>
                    <div class="col-md-8">
                        <form method="POST" action="{{ route('search') }}" class="row g-3 float-end">

                            @csrf
                            <div class="col-auto">
                                <input type="text" style="height: 40px" name="searchValue" required
                                    class="form-control" id="inputPassword2" placeholder="Search Value">

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
                            <th> Invoice # </th>
                            <th> Customer </th>
                            <th> Sales Person </th>
                            <th> Target Amount</th>
                            <th> Amount Collected </th>
                            <th> Balance </th>
                            <th> Status </th>
                            <th> Updated </th>
                            <th> Edit </th>
                            <th> Created </th>
                            <th> More </th>
                        </tr>
                    </thead>
                    <tbody>

                        @if ($invoices->count())
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td> {{ $invoice->invoice_number }} </td>
                                    <td> {{ $invoice->customer->name }} </td>
                                    <td> {{ $invoice->user->name }} </td>
                                    <td> {{ $invoice->amount + $invoice->debit_adjustment - $invoice->credit_adjustment }}
                                    </td>
                                    <td> {{ $invoice->amount_paid }} </td>
                                    <td> {{ $invoice->amount + $invoice->debit_adjustment - $invoice->credit_adjustment - $invoice->amount_paid }}
                                    </td>
                                    <td>
                                        @if ($invoice->status == 'PARTIALYPAID')
                                            <span class="badge badge-warning"><i class="fa fa-check-circle"></i>
                                                {{ $invoice->status }}</span>
                                        @endif
                                        @if ($invoice->status == 'PAID')
                                            <span class="badge badge-success"><i class="fa fa-check-circle"></i>
                                                {{ $invoice->status }}</span>
                                        @endif
                                        @if ($invoice->status == 'NOTPAID')
                                            <span class="badge badge-danger"><i class="fa fa-check-circle"></i>
                                                {{ $invoice->status }}</span>
                                        @endif
                                    </td>
                                    <td> {{ $invoice->user->name }} </td>
                                    <td> {{ $invoice->updated_at->diffForHumans() }} </td>
                                    <td> {{ $invoice->created_at->diffForHumans() }} </td>
                                    @if (auth()->user()->hasRole('Treasurer'))
                                        <td> <a class="btn btn-warning"
                                                href="{{ route('invoices.review', $invoice->id) }}"><i
                                                    class="fa fa-edit"></i> Review </a> </td>
                                    @endif
                                    @if (!auth()->user()->hasRole('Treasurer'))
                                        <td> <a class="btn btn-warning"
                                                href="{{ route('invoices.edit', $invoice->id) }}"><i
                                                    class="fa fa-edit"></i> Edit </a> </td>
                                    @endif
                                    <td> <a class="btn btn-primary"
                                            href="{{ route('invoices.view', $invoice->id) }}">More <i
                                                class="mdi mdi-arrow-right"></i> </a> </td>

                                </tr>
                            @endforeach
                        @else
                            <div class="alert alert-danger">No records</div>
                        @endif


                    </tbody>
                </table>
                @if (!empty($invoices->links))
                    <div class="d-flex justify-content-center">
                        {!! $invoices->links('vendor.pagination.bootstrap-5') !!}
                    </div>
                @endif


            </div>
        </div>
    </div>
</x-app-layout>
