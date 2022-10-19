@extends('layouts.app')
@section('content')

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>{{ $landlord->name }}</h3>		
			</div>
			<hr>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
            @include('landlords/details')
        </div>
	</div>
   
</div>

@endsection
           
