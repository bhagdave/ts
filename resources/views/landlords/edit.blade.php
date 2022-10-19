@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Edit Landlord</h3>
				<form action="/landlord/{{ $landlord->id}}/delete" method="post" class="py-2">
					<input class="btn btn-outline-danger" type="submit" value="Delete Landlord" onclick="return confirm('Are you sure you want to delete this item?');" />
					{!! method_field('delete') !!}
					{!! csrf_field() !!}
				</form>
			</div>
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<form method="POST" action="/landlord/{{ $landlord->id }}/update" enctype="multipart/form-data">
		<div class="row">
			<div class="col-sm-12 col-md-9 pt-2 pb-2">
				
				@csrf
				<div class="form-group">
					<label for="name">Landlord Name</label>
					<input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" aria-describedby="nameHelp" placeholder="Alex Example" value="{{  $landlord->name }}" required>
					@error('name')
					<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
				<div class="row ">
					<div class="form-group col-xs-12 col-sm-6">
						<label for="email">Landlord Email</label>
						<input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" aria-describedby="emailHelp" value="{{  $landlord->email }}" required>
						<small id="emailHelp" class="form-text text-muted">An invitation to join the stream from this property which will sent to this email address.</small>
						@error('name')
						<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group col-xs-12 col-sm-6">
						<label for="phone">Landlord Phone</label>
						<input type="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" aria-describedby="phoneHelp" value="{{  $landlord->phone }}">
						@error('name')
						<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
				</div>
				<div class="form-group">
					<label for="notes">Notes</label>
					<textarea class="form-control  @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" value="">{{ $landlord->notes }}</textarea>
					<small id="notesHelpNotes" class="form-text text-muted">Use this area to record any personal notes about the landlord.</small>
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
				
			</div>
						<div class="col-sm-12 col-md-3 pt-2 pb-2">
							<div class="card mt-2">
  <div class="card-body">
    <h5 class="card-title">Help</h5>
    <h6 class="card-subtitle mb-2 text-muted">Editing a Landlord</h6>
    <p class="card-text">Enter basic details about the landlord here. To add a property to this landlord, go to Properties, then click 'Add Property'.</p></p>
  </div>
</div>
			</div>
		</div>
	</form>
		</div>
	</div>
	
	
</div>
@endsection
