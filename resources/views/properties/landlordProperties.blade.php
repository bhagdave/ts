@extends('layouts.app')
@section('content')
<div class="container">
	@if($properties->count() > 0)
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Landlord Properties</h3>
				<a href="property/create" class="btn btn-outline-primary"><span class="oi oi-plus"></span> Add Property</a>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<div class="table-responsive pt-3">
				<table class="table">
					<tbody>
						<thead>
							<tr>
								<th scope="col">Name</th>
								<th scope="col">Type</th>
								<th scope="col">Address</th>
								<th scope="col">Issues</th>
								<th scope="col">Tenants</th>
								<th scope="col"></th>
							</tr>
						</thead>
						@foreach ($properties as $property)
						<tr>
							<th scope="row"><a href="/property/{{ $property ->id}}">{{ $property->propertyName }}</h3></a></th>
							<td>{{ $property->propertyType }}
								@if($property->agent != null)
								<span class="badge badge-pill badge-warning"><span class="oi oi-star"> Managed</span>
								@endif
							</td>
							<td>{{ $property->inputAddress }}, {{ $property->inputPostCode }}</td>
							<td><a href="/propertyissues/{{ $property ->id}}"><span class="oi oi-cog"></span> {{count($property->issues)}}</a>
</td>
							<td>
								@foreach ($property->tenants as $tenant)
								<a href="/tenant/{{ $tenant->id }}">
									@if ($tenant->user)
									<img src="{{$tenant->user->profileImage}}" class="rounded-circle" width="25" height="25">
									@else
								<svg class="bd-placeholder-img rounded-circle" width="25" height="25" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 25x25"><title>Placeholder</title><rect width="100%" height="100%" fill="#777"></rect><text x="20%" y="60%" fill="#777" dy=".1em" style="fill: white;font-size: 10px;"></text></svg>
								@endif
								
							</a>
							@endforeach
							
						</td>
						<td class="text-right">
							<a class="btn btn-outline-primary btn-sm" data-toggle="collapse" href="#collapseExample-{{$loop->iteration}}" role="button" aria-expanded="false" aria-controls="collapseExample-{{$loop->iteration}}">
								<span class="oi oi-chevron-bottom"></span>
							</a>
						</td>
					</tr>
					<tr class="collapse" id="collapseExample-{{$loop->iteration}}">
						<td colspan="6">
							<div class="row">
								<div class="col-sm-12 col-md-12">
									<div class="row">
                                        <div class="col-sm-12 py-4">
                                            @include('properties/card')
										</div>
									</div>
								</div>
							</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		
	</div>
</div>
@else

<div class="row my-md-5 justify-content-md-center">
	<div class="col-sm-12 col-md-6 align-self-center text-center">
		<div class="my-md-3 bg-white rounded">
			<script src="https://fast.wistia.com/embed/medias/fgce3k82ao.jsonp" async></script><script src="https://fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_fgce3k82ao videoFoam=true" style="height:100%;position:relative;width:100%">&nbsp;</div></div></div>

			<div class="p-5">

			<h3>No properties yet</h3>
			
			<h4 class="font-weight-light pt-2">Add a property to get started using Tenancy Stream</h4>
			<a href="property/create" class="btn btn-link btn-lg mt-2">Add Property</a>
		</div>
		</div>
	</div>
</div>


@endif
<div class="row">
	<div class="col-sm-12 d-flex justify-content-center">
		{{ $properties->links() }}
	</div>
</div>
@endsection
