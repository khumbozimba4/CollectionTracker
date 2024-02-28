<x-app-layout>

<div class="page-header">
    <h3 class="page-title"> New Invoice </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('invoices')}}">Invoices</a></li>
            <li class="breadcrumb-item active" aria-current="page">New Invoice</li>
        </ol>
    </nav>
</div>
{{-- NEW ADMIN FORM --}}
    @if(session('feedback'))
        <div class="alert alert-success">
        {{ session('feedback') }}
        </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">
    {{ session('error') }}
    </div>
@endif
    <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">

                    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                    <form class="forms-sample" action="{{route('invoices.new')}}" method="post">
                    @csrf
                    <div class="row">
                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Invoice Number</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Invoice Number" name="invoice_number" style="@error('invoice_number')border:1px red solid;@enderror" value="{{ old('name') }}">
                         @error('invoice_number')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>

                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Invoice Amount</label>
                        <input type="number" min="1" class="form-control" id="exampleInputName1" placeholder="Invoice Amount" name="amount" style="@error('amount')border:1px red solid;@enderror" value="{{ old('email') }}">
                        @error('amount')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>
                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Credit Adjustment</label>
                        <input type="number" min="0" class="form-control" id="exampleInputName1" placeholder="Credit Adjustment" name="credit_adjustment" style="@error('credit_adjustment')border:1px red solid;@enderror" value="{{ old('email') }}">
                        @error('credit_adjustment')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>
                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Debit Adjustment</label>
                        <input type="number" min="0" class="form-control" id="exampleInputName1" placeholder="Debit Adjustment" name="debit_adjustment" style="@error('debit_adjustment')border:1px red solid;@enderror" value="{{ old('email') }}">
                        @error('debit_adjustment')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>

                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Payment Status</label>
                        <select class="form-control" name="status" style="@error('status')border:1px red solid;@enderror">
                          <option value="" selected>Choose Payment Status</option>
                              <option selected value="NOTPAID">NOT PAID</option>
                              <option value="PARTIALYPAID">PARTIALY PAID</option>
                 
                       </select>           
                       @error('status')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>

                     

                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Sales Person</label>
                        <select class="form-control" name="user_id" id="sales-person-select" style="@error('user_id')border:1px red solid;@enderror">
                              <option value="" selected>Choose Sales Person</option>
                                @foreach ($sales as $item)
                                  <option value="{{$item->id}}">{{$item->name}}</option>
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
                        <select class="form-control" disabled id="customer-list" name="customer_id" required style="@error('customer_id')border:1px red solid;@enderror">
                              
                        </select>
                        @error('customer_id')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>

                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Amount Paid</label>
                        <input type="number" min="0" class="form-control" id="exampleInputName1" placeholder="Amount Paid" name="amount_paid" style="@error('amount_paid')border:1px red solid;@enderror" value="{{ old('email') }}">
                        @error('amount_paid')
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