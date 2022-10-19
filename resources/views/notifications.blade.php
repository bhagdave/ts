@extends('layouts.app')
@section('content')
<main role="main" class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Notifications</h3>  <small class="text-muted"> <span class="oi oi-clock"></span> Last Updated {{Carbon\Carbon::now()->format('g:i a')}} </small>
			</div>
		</div>
	</div>
    <user-activities ref="activities"></user-activities>
</main>
@endsection
