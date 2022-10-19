@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Edit @if ($tenant->user !=null) {{$tenant->user->firstName}} {{$tenant->user->lastName}} @else {{$tenant->name}} @endif</h3>
				<form action="/tenant/{{ $tenant->id}}/delete" method="post" class="py-2">
					<input class="btn btn-outline-danger" type="submit" value="Delete Tenant" onclick="return confirm('Are you sure you want to delete this item?');" />
					{!! method_field('delete') !!}
					{!! csrf_field() !!}
				</form>
			</div>
			<hr>
		</div>
	</div>
	<form method="POST" action="/tenant/{{ $tenant->id }}/update" enctype="multipart/form-data">
		<div class="row">
			
			<div class="col-sm-12 col-md-8 pt-2 pb-2">
				
				@csrf
				<div class="form-group">
					<label for="property_id">Property</label>
					<select id="property_id" name="property_id" class="form-control @error('property_id') is-invalid @enderror">
						<option value="{{$tenant->property_id }}">{{ $tenant->property->propertyName}}</option>
						<option value="">Select an option below to move the tenant to another property</option>
						@foreach ($properties as $property)
							<option value="{{ $property->id}}">{{ $property->propertyName}}</option>
						@endforeach
					</select>
					@error('rentDueInterval')
					<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
				<div class="row ">
					<div class="form-group col-xs-12 col-sm-6">
						<label for="email">Tenant Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" aria-describedby="emailHelp" value="{{  $tenant->email }}" 
                        @if (isset($tenant->sub)) disabled
                        >
                        <small id="emailHelp" class="form-text text-muted">To update email, please contact support.</small>
                        @else
                        required >
                        <small id="emailHelp" class="form-text text-muted">Updating the email will resend the invite.</small>
                        @endif
						@error('email')
						<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group col-xs-12 col-sm-6">
						<label for="phone">Tenant Phone</label>
						<input type="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" aria-describedby="phoneHelp" value="{{  $tenant->phone }}">
						@error('phone')
						<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
				</div>
				<div class="form-group">
					<label for="moveInDate">Tenancy Start Date</label>
					<input type="date" name="moveInDate" class="form-control date @error('moveInDate') is-invalid @enderror" id="moveInDate" aria-describedby="moveInDateHelp" value="{{  $tenant->moveInDate }}">
					@error('moveInDate')
					<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
				<div class="row">
					<div class="form-group col-xs-12 col-sm-6">
						<label for="rentAmount">Rent</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">£</span>
							</div>
							<input type="number" id="rentAmount" name="rentAmount"  class="form-control @error('rentAmount') is-invalid @enderror" aria-label="Amount (to the nearest pound)" value="{{ $tenant->rentAmount }}">
							<div class="input-group-append">
								<span class="input-group-text">.00</span>
							</div>
						</div>
						@error('totalRent')
						<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group col-xs-12 col-sm-6">
						<label for="rentDueInterval">Rent Due Interval</label>
						<select id="rentDueInterval" name="rentDueInterval" class="form-control @error('rentDueInterval') is-invalid @enderror" value="{{ $tenant->rentDueInterval }}">
							<option>{{ $tenant->rentDueInterval }}</option>
							<option>Weekly</option>
							<option>Monthly</option>
							<option>Quarterly</option>
							<option>6 Months</option>
							<option>Yearly</option>
							<option>Termly</option>
						</select>
						@error('rentDueInterval')
						<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
				</div>

				
				<div class="form-group">
					<label for="notes">Notes</label>
					<textarea class="form-control  @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" value="">{{ $tenant->notes }}</textarea>
					<small id="notesHelpNotes" class="form-text text-muted">Use this area to record any personal notes about the tenant.</small>
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
				
			</div>



		</div>
	</form>
	
</div>
@endsection
