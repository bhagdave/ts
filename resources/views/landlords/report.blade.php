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
            <form action="/landlord/{{$landlord->id}}/generate-report" method="POST">
                @csrf
                <label for="month">Month</label>
                <input id="month" type="month" value="2021-01" class="form-control" name="month">
                <button class="mt-3 btn btn-primary">Generate Report</button>
            </form>
        </div>
	</div>
   
</div>
@endsection
