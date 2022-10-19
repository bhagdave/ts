@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Edit {{ $property->propertyName }}</h3>
				<form action="/property/{{ $property->id}}/delete" method="post" class="py-2">
					<input class="btn btn-outline-danger" type="submit" value="Delete Property" onclick="return confirm('Are you sure you want to delete this item?');" />
					{!! method_field('delete') !!}
					{!! csrf_field() !!}
				</form>
			</div>
			<hr>
		</div>
	</div>
	<form method="POST" action="/property/{{ $property->id }}/update" enctype="multipart/form-data">
        @csrf
        <div class="row">
            @if ($property->profileImage)
                <div class="col-sm-12 col-md-3 pt-2 pb-2">
                    <div class="border rounded p-2 bg-light">
                        <img src="{{ $property->profileImage}}" style="width:100%" class="">
                    </div>
                </div>
            @else
                <div class="col-sm-12 col-md-3 pt-2 pb-2">
                    <div class="border rounded p-2 bg-light">
                        <img src="https://via.placeholder.com/150/" style="width:100%" class="">
                        <div class="form-group">
                            <label for="profileImage" class="">Property Image</label>
                            <input id="profileImage" type="file" class="form-control" name="profileImage">
                        </div>
                        <small id="profileImageHelp" class="form-text text-muted">Upload a welcoming picture of your property to help welcome tenants!</small>
                    </div>
                </div>
            @endif
            <div class="col-sm-12 col-md-9 pt-2 pb-2">
				<div class="form-group">
					<label for="propertyName">Property Name</label>
					<input type="text" name="propertyName" class="form-control @error('propertyName') is-invalid @enderror" id="propertyName" aria-describedby="propertyNameHelp" placeholder="Example House" value="{{ $property->propertyName }}" required>
					@error('propertyName')
                        <div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
				@if ($property->profileImage)
                    <div class="form-group">
                        <label for="profileImage" class="">Property Image</label>
                        <input id="profileImage" type="file" class="form-control" name="profileImage">
                    </div>
				@endif
				
				<h4>Location</h4><hr>
                <div class="form-group">
                    <label for="inputAddress">Address 1</label>
                    <input type="text" class="form-control @error('inputAddress') is-invalid @enderror" id="inputAddress" name="inputAddress" placeholder="1234 Main St" value="{{ $property->inputAddress }}" required>
                    @error('inputAddress')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="inputAddress2">Address 2</label>
                    <input type="text" class="form-control @error('inputAddress2') is-invalid @enderror" id="inputAddress2" name="inputAddress2" placeholder="Apartment, studio, or floor" value="{{ $property->inputAddress2 }}">
                    @error('inputAddress2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group row">
                    <div class="form-group col-md-6">
                        <label for="inputCity">City</label>
                        <input type="text" class="form-control @error('inputCity') is-invalid @enderror" id="inputCity" name="inputCity" value="{{ $property->inputCity }}" required>
                        @error('inputCity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPostCode">Post Code</label>
                        <input type="text" name="inputPostCode"  class="form-control  @error('inputPostCode') is-invalid @enderror" id="inputPostCode" value="{{ $property->inputPostCode }}" required>
                        @error('inputPostCode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

				@if ($user->isAn('agent'))
                    <div class="form-group">
                        <label for="addToLandlordAccount">Landlord</label>
                        <select id="addToLandlordAccount" name="addToLandlordAccount" class="form-control @error('addToLandlordAccount') is-invalid @enderror">
                            <option value="">Agent Account</option>
                            @foreach ($landlords as $landlord)
                                <option value="{{ $landlord->user_id}}">{{ $landlord->name}}</option>
                            @endforeach
                        </select>
                        <small id="addToLandlordAccount" class="form-text text-muted">
                            You can only attach landlords to a property once they have accepted their invite. 
                            <a href="/landlord/create">You can add a new landlord here.</a>
                        </small>
                        @error('addToLandlordAccount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
				@endif

                <div class="form-group">
                    <label for="propertyNotes">Notes</label>
                    <textarea class="form-control  @error('propertyNotes') is-invalid @enderror" id="propertyNotes" name="propertyNotes" rows="3" value="">{{ $property->propertyNotes }}</textarea>
                    <small id="propertyTypeHelp" class="form-text text-muted">Use this area to record any personal notes about the property.</small>
                </div>


				<button type="submit" class="btn btn-primary">Submit</button>
				
			</div>
		</div>
			
    </form>
		
</div>
@endsection
