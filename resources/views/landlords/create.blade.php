@extends('layouts.app')
@section('content')

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Add Landlord</h3>
			</div>
			<hr>
		</div>
	</div>

	<div class="row">

		<div class="col-sm-12 col-md-8">
				<form method="POST" action="/landlord/store" enctype="multipart/form-data">
            		@csrf

            	<div class="form-group">
				    <label for="name">Landlord Name</label>
				    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" aria-describedby="nameHelp" placeholder="Alex Example" value="{{ old('name') }}" required>

				    @error('name')
	    				<div class="invalid-feedback">{{ $message }}</div>
					@enderror
			   </div>

			   <div class="form-group">
				    <label for="email">Landlord Email</label>
				    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" aria-describedby="emailHelp" value="{{ old('email') }}" required>
				    <small id="emailHelp" class="form-text text-muted">An invitation to join will sent to this email address.</small>

				    @error('email')
	    				<div class="invalid-feedback">{{ $message }}</div>
					@enderror
			   </div>

			   <button type="submit" class="btn btn-primary">Submit</button>

            	</form>
            </div>

            <div class="col-sm-12 col-md-4">
         	    <div class="card mt-2">
                    <div class="card-body">
                        <h5 class="card-title">Help</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Adding Landlords</h6>
                        <p class="card-text">Enter basic details about the landlord here. We'll then send them an invitation to access Tenancy Stream automatically. You can add details like contact information, properties and notes in the next step.</p>
                    </div>
                </div>
            </div>
	</div>
   
</div>

@endsection
           
