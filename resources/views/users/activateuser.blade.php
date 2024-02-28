<x-app-layout>

<div class="page-header">
<h3 class="page-title"> Activate User </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('users')}}">Users</a></li>
            <li class="breadcrumb-item"><a href="{{route('users.view', $user->id)}}">{{$user->name}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Activate</li>
        </ol>
    </nav>
</div>

{{-- NEW ADMIN FORM --}}
    @if(session('feedback'))
        <div class="alert alert-success ">
        {{ session('feedback') }}
        </div>
    @endif

    <div class="alert alert-warning text-center">
        Are you sure you want to activate the following user? <br>
       <b> {{$user->name.' - '.$user->email}} </b>

    </div>

    <form class="forms-sample text-center" action="{{route('users.activate', $user->id)}}" method="post">
        @csrf
    
        <a href={{route('users.view', $user->id)}} class="btn btn-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-success me-2"><i class="mdi mdi-check-circle"></i> Activate</button>
    </form>
   
{{-- END OF NEW ADMIN FORM --}}
</x-app-layout>