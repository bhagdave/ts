@extends('layouts.app')
@section('content')
<main role="main" class="container">
	<div class="row">
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h3>Agents Admin</h3>
            <form action="/agency/admin/search/agents" method="post">
                @csrf
                <input type="text" name="search" required value="{{ $search ?? ''}}" placeholder="Enter part of the name" class="formcontrol">
                @if (isset($userType))
                    <input type="hidden" name="usertype" value="{{$userType}}">
                @endif
                <button class="btn btn-primary">Search</button>
            </form> 
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive pt-3">
                <table class="table">
                    <tbody>
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Created</th>
                                <th scope="col">Last Active</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        @foreach ($agents as $agent)
                        <tr>
                            <td>{{$agent->name}}</td>
                            <td>{{Carbon\Carbon::parse($agent->created_at)->diffForHumans()}}</td>
                            <td>{{Carbon\Carbon::parse($agent->user->last_activity)->diffForHumans()}}</td>
                            <td>
                                <form action="/agency/admin/delete/agent/{{ $agent->id }}" method="post">
                                    <input class="btn btn-outline-danger btn-block" type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this agent?');" />
                                    {!! method_field('delete') !!}
                                    {!! csrf_field() !!}
                                </form>
                                <a href="/directmessage/agent/{{ $agent->id  }}" class="btn btn-outline-primary btn-block mt-1">
                                    Message
                                </a>
                            </td>	
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if (!isset($search))
                {{ $agents->links() ?? ''}}
            @endif
        </div>
    </div>
</main>
@endsection
