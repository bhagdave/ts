@extends('layouts.app')
@section('content')
<div class="container">

    <div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<div>

                    @if($issue->extra_attributes->status == "Open") 
                        <span class="badge badge-pill badge-primary mr-3">{{ $issue->extra_attributes->status }}</span> 
                    @elseif($issue->extra_attributes->status == "Pending Landlord")
                        <span class="badge badge-pill badge-warning mr-3">{{ $issue->extra_attributes->status }}</span> 
                    @elseif($issue->extra_attributes->status == "Pending Contractor")
                        <span class="badge badge-pill badge-warning mr-3">{{ $issue->extra_attributes->status }}</span> 
                    @elseif($issue->extra_attributes->status == "On-Going")
                        <span class="badge badge-pill badge-dark mr-3">{{ $issue->extra_attributes->status }}</span> 
                    @elseif($issue->extra_attributes->status == "Closed")
                        <span class="badge badge-pill badge-danger mr-3">{{ $issue->extra_attributes->status }}</span> 	
                    @endif

                    Issue opened about {{Carbon\Carbon::parse($issue->created_at)->diffForHumans()}} by <strong>{{ $issue->extra_attributes->author ?? "Unknown Author"}}</strong>
                </div>
				<div>
                    <form action="/issue/{{ $issue->id}}/delete" method="post" class="py-2">
                        @if ($user->isAn('agent')  || $user->isAn('landlord') || $user->isAn('admin') || $user->isAn('contractor'))
                            <a href="/issue/{{ $issue->id}}/edit" class="btn btn-sm btn-outline-primary">Edit Issue</a>
                            @if ($user->userType=="Agent" || $user->userType=="Admin")
                                <input class="btn btn-outline-danger btn-sm " type="submit" value="Delete Issue" onclick="return confirm('Are you sure you want to delete this issue?');" />
                                {!! method_field('delete') !!}
                                {!! csrf_field() !!}
                            @endif
                        @endif
                    </form>
                </div>
				
			</div>
		</div>
	</div>

	<div class="row pt-3">
		<div class="col-sm-12 col-md-8 py-3 bg-white rounded shadow-sm">
            <div class="row">
                <div class="col-sm-12">			
                    <h3>{{ $issue->extra_attributes->title ?? "Issue"}}</h3>

                    <div class="ml-2">
                        <p>{{ $issue->extra_attributes->mainDescription}}</p>
                    </div>

                    <div class="my-3">
                        <h5 class="border-bottom pt-3 pb-3">Issue Log</h5>
                        @foreach ($history as $item)
                            <div class="row py-2 media-body pb-2 mb-0 ml-3 border-bottom mr-1 ">
                                <div class="col-auto pr-0">
                                    <img src="{{$item->properties['profileImage'] ?? '/images/default.png' }}" width="40" height="40" class="rounded-circle border-0">
                                </div>
                                <div class="col">
   				 	                <strong> {{$item->properties['firstName']}} {{$item->properties['lastName']}}</strong> 
                                    <span class="text-muted pl-1"> {{Carbon\Carbon::parse($item->updated_at)->diffForHumans()}} </span> 
                                    <span class="badge badge-pill badge-light">{{$item->properties['userType']}}</span> 
   				 	   				<div class="row">
                                        <div class="col">
                                            <p class="card-text">{{$item->description}}  
                                                @if($item->properties['issueStatus'] == "Open") 
                                                    <span class="badge badge-pill badge-primary ml-3 float-right">{{ $item->properties['issueStatus'] }}</span> 
                                                @elseif($item->properties['issueStatus'] == "Pending Landlord")
                                                    <span class="badge badge-pill badge-warning ml-3 float-right">{{ $item->properties['issueStatus'] }}</span> 
                                                @elseif($item->properties['issueStatus'] == "Pending Contractor")
                                                    <span class="badge badge-pill badge-warning ml-3 float-right">{{ $item->properties['issueStatus'] }}</span> 
                                                @elseif($item->properties['issueStatus'] == "On-Going")
                                                    <span class="badge badge-pill badge-dark ml-3 float-right">{{ $item->properties['issueStatus'] }}</span> 
                                                @elseif($item->properties['issueStatus'] == "Closed")
                                                    <span class="badge badge-pill badge-danger ml-3 float-right">{{ $item->properties['issueStatus'] }}</span> 	
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <h5 class="border-bottom pt-3 pb-3">Quick Update</h5>
                        <form method="POST" action="{{ url("issue/$issue->id/add-log-entry") }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">Your comment</label>
                                <textarea class="form-control  @error('description') is-invalid @enderror" 
                                          name="description" 
                                          id="description" 
                                          placeholder="Enter what has changed with the issue" 
                                          required>
                                </textarea>

                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @if ($user->isAn('agent')  || $user->isAn('landlord') || $user->isAn('contractor'))
                                <div class="form-group">
                                    <label for="status">Change Status from {{ $issue->attributes }} to:</label>
                                    <select class="@error('status') is-invalid @enderror form-control" id="inputGroupSelect01" name="status">
                                        <option value="Open" @if($issue->attributes == "Open") selected @endif >Open</option>
                                        <option value="Pending Landlord" @if($issue->attributes == "Pending Landlord") selected @endif >Pending Landlord</option>
                                        <option value="Pending Contractor" @if($issue->attributes == "Pending Contractor") selected @endif >Pending Contractor</option>
                                        <option value="On-Going" @if($issue->attributes == "On-Going") selected @endif >On-Going</option>
                                        <option value="Closed" @if($issue->attributes == "Closed") selected @endif >Closed</option>
                                    </select>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>

                    <div class="my-3">
                        @if (!isset($issue->contractor))
                            @if (isset($issue->bidders) && ( $user->isAn('agent') || $user->isAn('landlord') ))
                                <h4>Contractors who have bid for this job</h4>
                                <div class="form-row">
                                    <table>
                                        <tr>
                                            <th>Name</th>
                                            <th>Company</th>
                                            <th>Email</th>
                                            <th>
                                            </th>
                                        </tr>
                                        @foreach($issue->bidders as $contractor)
                                            <tr>
                                                <td>{{$contractor->name}}</td> 
                                                <td>{{$contractor->company}}</td> 
                                                <td>{{$contractor->email}}</td>
                                                <td><a class="openissue button btn btn-primary" href="/issue/{{$issue->id}}/acceptbid/{{$contractor->id}}">Accept</a></td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>	
            </div>
		</div>
		@if ($user->isAn('agent')  || $user->isAn('landlord') || $user->isAn('admin') || $user->isAn('contractor'))
            <div class="col-sm-12 col-md-3 offset-md-1 pt-3 bg-white rounded shadow-sm">
                <div class="row border-bottom pt-2 pb-2">
                    <div class='col-6'>
                        <strong>Priority</strong>
                    </div>
                    <div class='col-6 text-right'>
                        <a href="/issue/{{ $issue->id}}/edit"><small>Edit</small></a>
                    </div>
                    <div class="col-12">
                        {{ $issue->extra_attributes->priority ?? "No Priority Set"}}
                    </div>
                </div>
                
                <div class="row border-bottom pt-2 pb-2">
                    <div class='col-6'>
                        <strong>Due Date</strong>
                    </div>
                    <div class='col-6 text-right'>
                        <a href="/issue/{{ $issue->id}}/edit"><small>Edit</small></a>
                    </div>
                    <div class="col-12">
                        {{ $issue->extra_attributes->duedate ?? "No Date Set"}}
                    </div>
                </div>

                <div class="row border-bottom pt-2 pb-2">
                    <div class='col-6'>
                        <strong>Category</strong>
                    </div>
                    <div class="col-12">
                        {{ $issue->category->name ?? "No Category Set"}}
                    </div>
                </div>

                <div class="row border-bottom pt-2 pb-2">
                    <div class='col-6'>
                        <strong>Contractor</strong>
                    </div>
                    <div class="col-12">
                        {{ $issue->contractor->name ?? "No Contractor Set"}}
                    </div>
                </div>

                @if($issue->extra_attributes->confidential == "true")
                <div class="row border-bottom pt-2 pb-2">
                    <div class='col-6'>
                        <strong>Confidential</strong>
                    </div>
                    <div class="col-12">
                        This issue is set as confidential and future updates will not be shared on the stream or visible by tenants or landlords.
                    </div>
                </div>
                @else
                <div class="row border-bottom pt-2 pb-2">
                    <div class='col-6'>
                        <strong>Public</strong>
                    </div>
                    <div class="col-12">
                        This issue is set as public and future updates will be shared on the stream. Landlords and tenants can see all updates.
                    </div>
                </div>
                @endif
            </div>
        @endif
</div>

@endsection
           
