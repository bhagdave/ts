@extends('layouts.app')
@section('content')
<main role="main" class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Admin</h3>
			</div>
		</div>
	</div>
    @include('admin/menu')
	<div class="row">
		<div class="col-sm-12">
			<div class="my-3 p-3 bg-white rounded shadow-sm">
                <h3>{{ $userType ?? 'User'}}s</h3>
                <form action="/admin/user/search" method="post">
                    @csrf
                    <input type="text" name="search" required value="{{ $search ?? ''}}" placeholder="Enter part of the email address" class="formcontrol">
                    @if (isset($userType))
                        <input type="hidden" name="usertype" value="{{$userType}}">
                    @endif
                    <button class="btn btn-primary">Search</button>
                </form> 
				<div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive pt-3">
                            <table class="table">
                                <tbody>
                                    <thead>
                                        <tr>
                                            <th scope="col">Email</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">User Type</th>
                                            <th scope="col">Agent</th>
                                            <th scope="col">Created</th>
                                            <th scope="col">Tools</th>
                                        </tr>
                                    </thead>
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->telephone}}</td>
                                        <td>{{$user->userType}}</td>
                                        <td>

                                            @if ($user->userType=="Agent") N/A @endif

                                            @if ($user->userType=="Tenant") {{$user->tenant->property->agent->name ?? "None"}} @endif

                                            @if ($user->userType=="Landlord") {{$user->landlord->agent->name ?? "None"}} @endif

                                        </td>
                                        <td>{{Carbon\Carbon::parse($user->created_at)->diffForHumans()}}</td>
                                        <td>


                                            <form action="/profile/delete/{{ $user->id }}" method="post">
                                                        
                                            <a href="{{$user->magicLink}}" class="btn btn-outline-success mr-3">Act as User</a>
                                            @if ($user->userType == "Agent" || $user->userType == "Tenant")
                                                        <input class="btn btn-outline-danger " type="submit" value="Delete User" onclick="return confirm('Are you sure you want to delete this user? You must clear database of conflicts first or this fails.');" />
                                                        {!! method_field('delete') !!}
                                                        {!! csrf_field() !!}
                                            @endif			
                                                    </form>


                                        </td>	

                                    </tr>
                                    
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if (!isset($search))
                            {{ $users->links() ?? ''}}
                        @endif
                    </div>
                </div>
			</div>
		</div>
	</div>
</main>
@endsection
