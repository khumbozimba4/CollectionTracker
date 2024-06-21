<x-app-layout>

    <div class="page-header">
        <h3 class="page-title"> Review Invoice </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('invoices')}}">Invoices</a></li>
                <li class="breadcrumb-item"><a href="{{route('invoices.view', $invoice->id)}}">{{$invoice->invoice_number}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Review</li>
            </ol>
        </nav>
    </div>
    {{-- NEW ADMIN FORM --}}
        @if(session('feedback'))
            <div class="alert alert-success">
            {{ session('feedback') }}
            </div>
        @endif
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">



              <div class="row">
                    <div class="col-md-12 col-lg-12 text-center">

                        <div class="nav-profile-text">
                            <h4 class="card-title">Amount Claimed to have been collected: <span class="text-danger"> MWK {{$invoice->current_amount_collected}}</span3</h4>

                        </div>
                    </div>

                                    <div class="col-md-3 col-lg-3">
                                        <label>Sales Person:</label>
                                        <p><b>{{$invoice->user->name}}</b></p>
                                    </div>

                                    <div class="col-md-3 col-lg-3">
                                        <label>Customer:</label>
                                        <p><b>{{$invoice->customer->name}}</b></p>
                                    </div>

                                    <div class="col-md-3 col-lg-3">
                                        <label>Invoice Amount:</label>
                                        <p><b>MWK {{ number_format(($invoice->amount+$invoice->debit_adjustment)-$invoice->crreview_adjustment,2,'.',',')}}</b></p>


                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <label>Amount Collected:</label>
                                        <p><b>MWK {{ number_format($invoice->amount_paid,2,'.',',')}}</b></p>
                                    </div>
                                </div>




              </div>
            </div>
          </div>
        <div class="col-12 grid-margin stretch-card">
                    <div class="card">

                      <div class="card-body">
                        <form class="forms-sample" action="{{route('invoices.review', $invoice->id)}}" method="post">


                        @csrf
                        <div class="row">

                          <div class="form-group col-md-6 col-lg-6">
                            <label for="exampleInputName1">Approve/Reject</label>
                            <select name="is_reviewed" required class="form-control" id="">
                                <option value="" selected>Select</option>
                                <option value="0">Approve</option>
                                <option value="1">Reject</option>
                            </select>
                          </div>

                          <div class="form-group col-md-6 col-lg-6">
                            <label for="exampleInputName1"> Amount Collected</label>
                            <input type="number" min="1" step="any" readonly class="form-control" id="exampleInputName1" placeholder="Invoice Amount Colected" name="current_amount_collected" style="@error('amount')border:1px red solid;@enderror" value="{{ $invoice->current_amount_collected }}">
                            @error('amount')
                                <div style="color: red;">
                                    {{ $message }}
                                </div>
                             @enderror
                          </div>

                          <div class="form-group col-md-12 col-lg-12">
                            <label for="exampleInputName1">Rejection Reason if any</label>
                            <textarea name="remarks" class="form-control" id="" cols="30" rows="10"></textarea>

                          </div>


                        </div>
                          <button type="submit" class="btn btn-primary me-2">Submit</button>
                        </form>
                      </div>
                    </div>
                  </div>
    {{-- END OF NEW ADMIN FORM --}}
    </x-app-layout>
