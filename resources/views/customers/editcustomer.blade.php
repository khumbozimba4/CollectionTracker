<x-app-layout>

<div class="page-header">
    <h3 class="page-title"> Edit Customer </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('customers')}}">Customers</a></li>
            <li class="breadcrumb-item"><a href="{{route('customers.view', $customer->id)}}">{{$customer->name}}</a></li>
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
                    <form class="forms-sample" action="{{route('customers.edit', $customer->id)}}" method="post">
                    @csrf
                    <div class="row">
                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Fullname</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Fullname" name="name" style="@error('name')border:1px red solid;@enderror" value="{{ $customer->name }}">
                         @error('name')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>

                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Email</label>
                        <input type="email" class="form-control" id="exampleInputName1" placeholder="Email" name="email" style="@error('email')border:1px red solid;@enderror" value="{{ $customer->email }}">
                        @error('email')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>

                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Phone Number</label>
                        <input type="number" class="form-control" id="exampleInputName1" placeholder="Phone Number" name="phone_number" style="@error('phone_number')border:1px red solid;@enderror" value="{{ $customer->phone_number }}">
                        @error('phone_number')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>

                   

                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Sales Person</label>
                        <select class="form-control" name="user_id" style="@error('user_id')border:1px red solid;@enderror">
                          <option value="{{$customer->user->id}}" selected>{{$customer->user->name}}</option>
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

                   

                      
                      
                    </div>
                      <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>
                  </div>
                </div>
              </div>
{{-- END OF NEW ADMIN FORM --}}
</x-app-layout>