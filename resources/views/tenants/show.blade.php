@extends('layouts.app')
@section('content')

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>@if ($tenant->user !=null) {{$tenant->user->firstName}} {{$tenant->user->lastName}} @else {{$tenant->name}} @endif</h3>
				@if ($user->userType=="Agent" || $user->userType=="Admin")
                    <form action="/tenant/{{ $tenant->id}}/delete" method="post" class="py-2">
                        <a href="/tenant/{{ $tenant->id}}/edit" class="btn btn-outline-primary">Edit Tenant</a>
                        <input class="btn btn-outline-danger" type="submit" value="Delete Tenant" onclick="return confirm('Are you sure you want to delete this item?');" />
                        {!! method_field('delete') !!}
                        {!! csrf_field() !!}
                    </form>
				@endif
				
			</div>
			<hr>
		</div>
	</div>

	<div class="row">
        <div class="col-sm-12">
            @include('tenants/details')
		</div>
	</div>
</div>

@endsection
           
