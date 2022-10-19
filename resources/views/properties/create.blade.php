@extends('layouts.app')
@section('content')
    @if (session('wizard') == 'property')
        <property-wizard></property-wizard>
    @endif
    <form method="POST" action="/property/store" enctype="multipart/form-data">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3>Add a New Property</h3>
                    </div>
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-8">
                    @csrf
                    <div class="form-group">
                        <label for="propertyName">Property Name</label>
                        <input type="text" name="propertyName" class="form-control @error('propertyName') is-invalid @enderror" 
                               id="propertyName" aria-describedby="propertyNameHelp" placeholder="Example House" value="{{ old('propertyName') }}" required>

                        @error('propertyName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        @if ($user->isAn('agent') ) 
                            <div class="form-group col-md-6 col-xs-12">
                                <label for="propertyType">Type of Property</label>
                                <select id="propertyType" name="propertyType" class="form-control @error('propertyType') is-invalid @enderror" required>
                                    <option>Single Let - Standard Residential Let</option>
                                    <option>HMO - Home of Multiple Occupancy</option>
                                    <option>Student - Student Residential Let</option>
                                    <option>Holiday Let - Includes AirBnB</option>
                                    <option>Rent2Rent</option>
                                    <option>Social Housing</option>
                                    <option>Other</option>
                                </select>
                                <small id="propertyTypeHelp" class="form-text text-muted">
                                    We collect this information in order to assist you with legal compliance. Choose the best fitting option, or select other.
                                </small>
                                @error('propertyType')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <input type="hidden" name="propertyType" value="Single Let - Standard Residential Let">
                        @endif

                        @if ($user->isAn('agent') ) 
                            <div class="form-group col-md-6 col-xs-12">
                                <label for="addToLandlordAccount">Landlord</label>
                                <select id="addToLandlordAccount" name="addToLandlordAccount" class="form-control @error('addToLandlordAccount') is-invalid @enderror">
                                    <option value="">Agent Access Only</option>
                                    @foreach ($landlords as $landlord)
                                        <option value="{{ $landlord->user_id}}">{{ $landlord->name}}</option>
                                    @endforeach
                                </select>
                                <small id="addToLandlordAccount" class="form-text text-muted">
                                    Do you want to allow the landlord to see activity and issues on this property? If so, select them here. <a href="/landlord/create">You can add a new landlord here.</a>
                                </small>
                                @error('addToLandlordAccount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                    </div>		  
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-8 mt-5">	  
                    <h5>Location</h5><hr>
                    <div class="form-group">
                        <label for="inputAddress">Address 1</label>
                        <input type="text" class="form-control @error('inputAddress') is-invalid @enderror" id="inputAddress" name="inputAddress" placeholder="1234 Main St" value="{{ old('inputAddress') }}" required>
                            @error('inputAddress')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                    </div>

                    <div class="form-group">
                        <label for="inputAddress2">Address 2</label>
                        <input type="text" class="form-control @error('inputAddress2') is-invalid @enderror" id="inputAddress2" name="inputAddress2" placeholder="Apartment, studio, or floor" value="{{ old('inputAddress2') }}">
                        @error('inputAddress2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputCity">City</label>
                            <input type="text" class="form-control @error('inputCity') is-invalid @enderror" id="inputCity" name="inputCity" value="{{ old('inputCity') }}" required>
                            @error('inputCity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="inputRegion">Region</label>
                            <select id="inputRegion" name="inputRegion" class="form-control @error('inputRegion') is-invalid @enderror" required>
                                <option>England</option>
                                <option>Scotland</option>
                                <option>Wales</option>
                            </select>
                            @error('inputRegion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-2">
                            <label for="inputPostCode">Post Code</label>
                            <input type="text" name="inputPostCode"  class="form-control  @error('inputPostCode') is-invalid @enderror" id="inputPostCode" value="{{ old('inputPostCode') }}" required>
                                @error('inputPostCode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                    </div>
                    <hr>

                    <button type="submit" class="btn btn-primary">Submit</button>

                    @if ($user->isAn('landlord'))
                        @if($agentOwned == true)
                            <div class="alert alert-success mt-3" role="alert">
                                By clicking submit, we'll contact your agent to start management on this property. 
                            </div>
                        @endif
                    @endif
                </div>    
            </div>
        </div>
	</form>
@endsection
           
