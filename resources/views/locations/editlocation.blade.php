<x-app-layout>

    <div class="page-header">
        <h3 class="page-title"> Edit Location </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('locations')}}">Locations</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$location->location_name}}</li>
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
        @if ($errors->any())
             @foreach ($errors->all() as $item)
                <li>{{$item}}</li>
             @endforeach
        @endif

        <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <form class="forms-sample" action="{{route('locations.update', ['id' => $location->id])}}" method="post">
                            @csrf
                        <div class="row">
                          <div class="form-group col-md-6 col-lg-6">
                            <label for="exampleInputName1">Fullname</label>
                            <input type="text" class="form-control" id="exampleInputName1" placeholder="Fullname" value="{{$location->location_name}}" name="location_name" style="@error('location_name')border:1px red solid;@enderror" value="{{ old('location_name') }}">
                             @error('location_name')
                                <div style="color: red;">
                                    {{ $message }}
                                </div>
                             @enderror
                          </div>
                          <div class="form-group col-md-6 col-lg-6">
                            <label for="exampleInputName1">Manager</label>
                                <select class="form-control" name="user_id" style="@error('user_id')border:1px red solid;@enderror">
                                    <option value="{{$location->user_id}}" selected>{{$location->manager->name}}</option>
                                    @foreach ($users as $item)
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
