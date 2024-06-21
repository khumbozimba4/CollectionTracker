<x-app-layout>

<div class="page-header">
    <h3 class="page-title"> Edit Invoice </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('invoices')}}">Invoices</a></li>
            <li class="breadcrumb-item"><a href="{{route('invoices.view', $invoice->id)}}">{{$invoice->invoice_number}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
</div>
{{-- NEW ADMIN FORM --}}
    @if(session('feedback'))
        <div class="alert alert-success">
        {{ session('feedback') }}
        </div>
    @endif
    <div class="col-12 grid-margin stretch-card">
                <div class="card">

                  <div class="card-body">
                    <form class="forms-sample" action="{{route('invoices.edit', $invoice->id)}}" method="post">

                      <h4>Invoice Amount: {{number_format(($invoice->amount+$invoice->debit_adjustment)-$invoice->credit_adjustment,2,'.',',')}}</h4> <br>
                      <h4>Credit Adjustment: {{ number_format($invoice->credit_adjustment,2,'.',',')}}</h4> <br>
                      <h4>Debit Adjustment: {{ number_format($invoice->debit_adjustment,2,'.',',')}}</h4> <br>
                    @csrf
                    <div class="row">
                      @if (auth()->user()->hasRole("admin"))

                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Invoice Number</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Invoice Number" name="invoice_number" style="@error('invoice_number')border:1px red solid;@enderror" value="{{ $invoice->invoice_number }}">
                         @error('invoice_number')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>

                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Invoice Amount</label>
                        <input type="number" min="1" step="any" class="form-control" id="exampleInputName1" placeholder="Invoice Amount" name="amount" style="@error('amount')border:1px red solid;@enderror" value="{{ $invoice->amount }}">
                        @error('amount')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>



                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Credit Adjustment</label>
                        <input type="number" min="0" step="any" class="form-control" id="exampleInputName1" placeholder="Credit Adjustment" name="credit_adjustment" style="@error('credit_adjustment')border:1px red solid;@enderror" value="{{ $invoice->credit_adjustment }}">
                        @error('credit_adjustment')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>
                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Debit Adjustment</label>
                        <input type="number" min="0" step="any" class="form-control" id="exampleInputName1" placeholder="Debit Adjustment" name="debit_adjustment" style="@error('debit_adjustment')border:1px red solid;@enderror" value="{{ $invoice->debit_adjustment }}">
                        @error('debit_adjustment')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>




                        <div class="form-group col-md-6 col-lg-6">
                          <label for="exampleInputName1">Sales Person</label>
                          <select class="form-control" name="user_id" id="sales-person-select" style="@error('user_id')border:1px red solid;@enderror">
                                  @foreach ($sales as $item)
                                  <option value="{{$item->id}}" {{$item->id == $invoice->customer->id ? 'selected' : ''}}>{{$item->name}}</option>
                                  @endforeach

                          </select>
                          @error('user_id')
                              <div style="color: red;">
                                  {{ $message }}
                              </div>
                          @enderror
                        </div>

                        <div class="form-group col-md-6 col-lg-6">
                          <label for="exampleInputName1">Choose customer</label>
                          <select class="form-control"  id="customer-list" name="customer_id" required style="@error('customer_id')border:1px red solid;@enderror">
                                <option selected value="{{$invoice->customer->id}}">{{$invoice->customer->name}}</option>
                          </select>
                          @error('customer_id')
                              <div style="color: red;">
                                  {{ $message }}
                              </div>
                          @enderror
                        </div>
                      @endif
                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Total Invoice Amount Collected</label>
                        <input type="number" min="0" step="any" readonly class="form-control" id="exampleInputName1" placeholder="Amount Collected" name="amount_paid" style="@error('amount_paid')border:1px red solid;@enderror" value="{{$invoice->amount_paid}}">
                        @error('amount_paid')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>
                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Amount Collected</label>
                        <input type="number" min="0" step="any" class="form-control" id="exampleInputName1" placeholder="Amount Collected" name="current_amount_collected" style="@error('current_amount_collected')border:1px red solid;@enderror" value="{{$invoice->current_amount_collected}}">
                        @error('current_amount_collected')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>

                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Payment Status</label>
                        <select class="form-control" name="status" style="@error('status')border:1px red solid;@enderror">
                             <option value="{{$invoice->status}}" selected>{{$invoice->status}}</option>
                              <option  value="NOTPAID">NOT PAID</option>
                              <option value="PARTIALYPAID">PARTIALY PAID</option>
                              <option value="PAID">FULLY PAID</option>
                       </select>
                       @error('status')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>
                    </div>
                      <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>
                  </div>
                </div>
              </div>
{{-- END OF NEW ADMIN FORM --}}
</x-app-layout>
