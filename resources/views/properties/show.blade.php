@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>{{ $property->propertyName }}</h3>
			</div>
			<hr>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<div class="row">
				<div class="col-sm-12 py-4">
                    @include('properties/card')
                </div>
			</div>
		</div>
	</div>
</div>
@endsection
