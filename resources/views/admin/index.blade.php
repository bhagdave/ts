@extends('layouts.app')
@section('content')
<main role="main" class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Admin</h3>
			</div>
		</div>
	</div>
    @include('admin/menu')
</main>
@endsection
