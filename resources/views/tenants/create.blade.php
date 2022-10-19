@extends('layouts.app')
@section('content')
@if (session('wizard') == 'property')
    <property-wizard></property-wizard>
@endif
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="d-flex align-items-center justify-content-between">
                <h3>Add Tenant @if(isset($property)) to {{$property->propertyName}} @endif</h3>
            </div>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-8">
            <form method="POST" class="submit-once" action="/tenant/store" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="name">Tenant Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" aria-describedby="nameHelp" placeholder="Alex Example" value="{{ old('name') }}" required>

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
               </div>

               <div class="form-group">
                    <label for="email">Tenant Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" aria-describedby="emailHelp" value="{{ old('email') }}" required>
                    <small id="emailHelp" class="form-text text-muted">An invitation to join the stream for this property will sent to this email address.</small>

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
               </div>

                
               <div class="row">
                   <div class="form-group col-xs-12 col-sm-6">
                       <div class="form-group">
                           <label for="phone">Tenant Phone</label>
                           <input type="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" aria-describedby="phoneHelp" value="{{  old('phone') }}">
                           @error('phone')
                               <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                       </div>
                   </div>
                   <div class="form-group col-xs-12 col-sm-6">
                       <div class="form-group">
                           <label for="moveInDate">Tenancy Start Date</label>
                           <input type="date" name="moveInDate" class="form-control date @error('moveInDate') is-invalid @enderror" id="moveInDate" aria-describedby="moveInDateHelp" value="{{  old('moveInDate') }}">
                           @error('moveInDate')
                               <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                       </div>
                   </div>
               </div>
               <div class="row">
                   <div class="form-group col-xs-12 col-sm-6">
                       <label for="rentAmount">Rent</label>
                       <div class="input-group">
                           <div class="input-group-prepend">
                               <span class="input-group-text">£</span>
                           </div>
                           <input type="number" id="rentAmount" name="rentAmount"  class="form-control @error('rentAmount') is-invalid @enderror" aria-label="Amount (to the nearest pound)" value="{{ old('rentAmount') }}">
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
                       <select id="rentDueInterval" name="rentDueInterval" class="form-control @error('rentDueInterval') is-invalid @enderror" value="{{ old('rentDueInterval') }}">
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
                   <textarea class="form-control  @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" value="">{{ old('notes') }}</textarea>
                   <small id="notesHelpNotes" class="form-text text-muted">Use this area to record any personal notes about the tenant.</small>
               </div>

               @if(isset($property))
                    <input type="hidden" name="property_id" value="{{$property->id}}">
               @else
                   <div class="form-group">
                        <label for="property_id">Property</label>
                        <select id="property_id" name="property_id" class="form-control @error('property_id') is-invalid @enderror">
                            @foreach ($properties as $property)
                                <option value="{{ $property->id}}">{{ $property->propertyName}}</option>
                            @endforeach
                        </select>
                        @error('property_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                   </div>
               @endif
               <button type="submit" class="disable-on-submit btn btn-primary">Submit</button>
               @if(isset($property))
                   <a href="/property" class="btn btn-danger">Cancel</a>
               @endif
            </form>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="card mt-2">
                <div class="card-body">
                    <h5 class="card-title">Help</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Adding Tenants</h6>
                    <p class="card-text">Enter details about the tenant here. We'll then send them an invitation to join the property. You can add details like contact information, tenancy length and notes but can also come back and do that later.</p></p>
                </div>
            </div>
        </div>

    </div>
</div>
  

@endsection
           
