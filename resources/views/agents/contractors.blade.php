@extends('layouts.app')
@section('content')
<main role="main" class="container">
	<div class="row">
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h3>Contractors Admin</h3>
            <p><small>Please note you will not be able to delete contractors who have open issues assigned to them.</small></p>
            <form action="/agency/admin/search/contractors" method="post">
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
                                <th scope="col">Company</th>
                                <th scope="col">Created</th>
                                <th scope="col">Last Active</th>
                                <th scope="col">Open Issues</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        @foreach ($contractors as $contractor)
                        <tr>
                            <td>{{$contractor->name}}</td>
                            <td>{{$contractor->company}}</td>
                            <td>{{Carbon\Carbon::parse($contractor->created_at)->diffForHumans()}}</td>
                            <td>{{Carbon\Carbon::parse($contractor->user->last_activity)->diffForHumans()}}</td>
                            <td>{{ $contractor->issues()->open()->count()  }}</td>
                            <td>
                                @if($contractor->issues()->open()->count() == 0)
                                <form action="/agency/admin/delete/contractor/{{ $contractor->id }}" method="post">
                                    <input class="btn btn-outline-danger btn-block" type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this contractor?');" />
                                    {!! method_field('delete') !!}
                                    {!! csrf_field() !!}
                                </form>
                                @endif
                            </td>	
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if (!isset($search))
                {{ $contractors->links() ?? ''}}
            @endif
        </div>
    </div>
</main>
@endsection
