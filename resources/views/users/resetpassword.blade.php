<x-app-layout>

    <div class="page-header">
        <h3 class="page-title"> Reset User Password </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('users')}}">Users</a></li>
                <li class="breadcrumb-item"><a href="{{route('users.view', $user->id)}}">{{$user->name}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reset Password</li>
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
                        <form class="forms-sample" action="{{route('users.resetpassword', $user->id)}}" method="post">
                        @csrf
                        <div class="row">
                          <div class="form-group col-md-6 col-lg-6">
                            <label for="exampleInputPassword4">New Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword4" placeholder="New Password" name="password" style="@error('password')border:1px red solid;@enderror" >
                             @error('password')
                                <div style="color: red;">
                                    {{ $message }}
                                </div>
                             @enderror
                          </div>

                          <div class="form-group col-md-6 col-lg-6">
                            <label for="exampleInputPassword4">Confirm Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword4" placeholder="Comfirm Password" name="password_confirmation" style="@error('password_confirmation')border:1px red solid;@enderror" >

                          </div>





                        </div>
                          <button type="submit" class="btn btn-primary me-2">Save</button>
                        </form>
                      </div>
                    </div>
                  </div>
    {{-- END OF NEW ADMIN FORM --}}
    </x-app-layout>
