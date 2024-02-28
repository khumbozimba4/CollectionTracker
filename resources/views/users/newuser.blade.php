<x-app-layout>

<div class="page-header">
    <h3 class="page-title"> New User </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('users')}}">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">New User</li>
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
                    <form class="forms-sample" action="{{route('users.new')}}" method="post">
                    @csrf
                    <div class="row">
                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Fullname</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Fullname" name="name" style="@error('name')border:1px red solid;@enderror" value="{{ old('name') }}">
                         @error('name')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>

                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Email</label>
                        <input type="email" class="form-control" id="exampleInputName1" placeholder="Email" name="email" style="@error('email')border:1px red solid;@enderror" value="{{ old('email') }}">
                        @error('email')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>

                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Phone Number</label>
                        <input type="number" class="form-control" id="exampleInputName1" placeholder="Phone Number" name="phone_number" style="@error('phone_number')border:1px red solid;@enderror" value="{{ old('phone_number') }}">
                        @error('phone_number')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>

                     

                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputName1">Role</label>
                        <select class="form-control" name="role" style="@error('role')border:1px red solid;@enderror">
                          <option value=""></option>
                                @foreach ($roles as $item)
                                  <option value="{{$item->name}}">{{$item->display_name}}</option>
                                @endforeach
                     
                        </select>
                        @error('role')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>

                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputPassword4">Password</label>
                        <input type="password" class="form-control " id="exampleInputPassword4" placeholder="Password" name="password" style="@error('password')border:1px red solid;@enderror">
                        @error('password')
                            <div style="color: red;">
                                {{ $message }}
                            </div>
                         @enderror
                      </div>

                      <div class="form-group col-md-6 col-lg-6">
                        <label for="exampleInputPassword4">Confirm Password</label>
                        <input type="password" class="form-control " id="exampleInputPassword4" placeholder="Password" name="password_confirmation">
                      </div>

                      
                      
                    </div>
                      <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>
                  </div>
                </div>
              </div>
{{-- END OF NEW ADMIN FORM --}}
</x-app-layout>