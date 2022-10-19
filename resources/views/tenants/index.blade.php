@extends('layouts.app')
@section('content')
<script type='text/javascript'>
    function copyToClipboard(text){
        console.log(text);
        navigator.clipboard.writeText(text).then(function(){
            alert("Link copied to clipboard!");
        }, function(){
            alert("Unable to copy link to clipboard!");
        });
        return true;
    }
</script>
<div class="container">
	@if($tenants->count() > 0)
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Your {{ $filter ?? ''  }} Tenants</h3>
                <form action="/tenant/search" class="bg-white" method="post">
                    @csrf
                    <input type="text" name="search" required value="{{ $search ?? ''}}" placeholder="Enter search text" class="formcontrol pr-5 mr-5">
                    <button class="btn btn-primary">Search</button>
                </form> 
                @if ($user->userType != "Tenant")
                   <a href="/tenant/create" class="btn btn-outline-primary"><span class="oi oi-plus"></span> Add Tenants</a>
				@endif
                @if (isset($search))
                   <a href="/tenants" class="btn btn-outline-primary">All Tenants</a>
                @endif 
			</div>
		</div>
	</div>
	<div class="row pt-2">
		<div class="col-sm-12">
			<div class="border-right border-warning border-top-0 border-bottom-0 bg-white rounded shadow-sm table-responsive">
				<table class="table">
					<tbody>
						@foreach ($tenants as $tenant)
						<tr class="bg-warning">
							<td>
                                @if ($tenant->user)
									@if ($tenant->user->profileImage)
                                        <img src="{{$tenant->user->profileImage}}" class="rounded-circle mr-3" width="25" height="25">
									@else
                                        <img src="/uploads/images/_default.jpg" class="rounded-circle mr-3" width="25" height="25">
                                    @endif
								@endif

                                <h4>@if ($tenant->user !=null) {{$tenant->user->firstName}} {{$tenant->user->lastName}} @else {{$tenant->name}} @endif</h4>
						        @if ($tenant->notes == "Deletion Requested")
                                    DELETED
                                @endif    

                                @if (!$tenant->sub)
                                    @if (isset($tenant->invitation))
                                        <span onclick="copyToClipboard('https://www.tenancystream.app/invite/{{$tenant->invitation->code}}')"  class="invite-link badge badge-pill badge-dark ml-2">Share Invitation link</span>
                                    @else
                                        <span class="invite-link badge badge-pill badge-dark ml-2">Pending Invitation</span>
                                    @endif
                                @endif


						</td>
                        <td>{{ $tenant->email}}</td>
                        @if ($tenant->property)
						    <td><a href="/property/{{$tenant->property->id}}">{{ $tenant->property->propertyName}}</a></td>
                        @endif

					</tr>
					<tr>
                        <td colspan="4">
                            @include('tenants/details')
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		
		<div class="d-flex align-items-center justify-content-between">
		</div>
		
	</div>
</div>
<div class="row">
	<div class="col-sm-12 d-flex justify-content-center">
		{{ $tenants->links() }}
	</div>
</div>
@else
<div class="row my-md-5 justify-content-md-center">
	<div class="col-sm-12 col-md-6 align-self-center text-center">
		<div class="my-md-3 bg-white rounded">
			<img src="https://images.unsplash.com/photo-1484712401471-05c7215830eb?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=950&q=100" class="w-100">
			<div class="p-5">
                @if (isset($properties))
                    @if( $properties->count() == 0)
                        <h3>No properties yet</h3>
                    
                        <h4 class="font-weight-light pt-2">Before you can add tenants, your next step is to create a property</h4>
                        <a href="/property/create" class="btn btn-link btn-lg mt-2">Add Property</a>
                    @else
                        <h3>No tenants yet</h3>
                        @if (isset($property))
                            <h4>{{$property->name}}
                        @endif
                    
                        <h4 class="font-weight-light pt-2">Add your first tenant</h4>
                        @if (!($user->isAn('tenant')))
                            <a href="/tenant/create" class="btn btn-link btn-lg mt-2">Add First Tenant</a>
                        @endif
                    @endif
                @endif
			</div>
		</div>
	</div>
</div>
@endif
</div>
@endsection
