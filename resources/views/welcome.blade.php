@extends('layouts.app-nosidebar')
@section('content')

<main role="main" class="container">
	<div class="row pt-3 justify-content-md-center">
		<div class="col-sm-12 col-md-6 align-self-center">
			<div class="my-3 p-5 bg-white rounded">
				<form action="/profile/create" method="post" enctype="multipart/form-data">
					{!! csrf_field() !!}
				
						<h2>Let's get you started!</h2>
						<hr>
						@if ($user->userType=="undefined" || $user->userType=="")
							Something went wrong. Please contact us.
						@endif

						@if ($user->userType=="Tenant")
                            @include('tenants.welcome')
                        @endif
						@if ($user->userType=="Agent" )
                            @include('agents.welcome')
						@endif

						@if ($user->userType=="Landlord")
                            @include('landlords.welcome')
                        @endif

						@if ($user->userType=="Contractor")
                            @include('contractors.welcome')
                        @endif
					
					
				</form>
			</div>
		</div>

	</div>
</main>
@endsection
