@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Issues at {{$properties->propertyName}} {{$properties->inputAddress}}</h3>
				<a href="/issues/create" class="btn btn-outline-primary"><span class="oi oi-plus"></span> Add Issue</a>
				<div class="row no-gutters align-items-center p-2"><a href="/issues/" class="btn btn-outline-secondary btm-sm w-100">Back to all issues</a></div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row flex-column-reverse flex-md-row">
		<div class="col-sm-4 col-md-8 col-lg-12">
			<div class="my-3 bg-white rounded shadow-sm">
				<div class="border-bottom border-gray mb-0">
					<issues-table :propertyId="'{{$properties->id}}'"></issues-table>
				</div>
			</div>

		</div>
	</div>
</div>
@endsection
