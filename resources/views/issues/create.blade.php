@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="d-flex align-items-center justify-content-between">
                <h3>Report Issue</h3>
            </div>
            <hr>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-12 col-md-12">
            <form class="submit-once" method="POST" action="/issue/store" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    @if ($user->userType == 'Tenant')
                        <input type="hidden"  name="property_id" value="{{ $user->tenant->property_id }}">
                    @else
                        <label for="property_id">Property</label>
                        <input dusk="property" class="form-control @error('property_id') is-invalid @enderror"id="property" name="property" value="{{ old('property') }}" autoComplete="off"  list="properties">
                        <datalist id="properties">
                            @foreach ($properties as $property)
                                <option data-value="{{ $property->id}}">{{ $property->propertyName}}</option>
                            @endforeach
                        </datalist>
                        <input type="hidden"  name="property_id" id="property-hidden">
                        @error('property_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    @endif
                </div>

                <div class="form-group">
                    <label for="name">Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="My Issue Title" value="{{ old('title') }}">
                    <small id="emailHelp" class="form-text text-muted">A good title is 'Broken Tap in Kitchen'</small>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
               </div>
               <div class="form-group">
                   <label for="name">Description</label>
                   <textarea class="form-control  @error('description') is-invalid @enderror" name="description" id="description" placeholder="Enter a brief description of the issue" value="{{ old('description') }}" required>{{ old('description')}}</textarea>
                   <small id="emailHelp" class="form-text text-muted">Type a detailed description of the issue that would enable someone to find and reproduce the issue</small>
                   @error('description')
                       <div class="invalid-feedback">{{ $message }}</div>
                   @enderror
              </div>

               @include('issues/form')
               <button type="submit" class="btn btn-primary mt-3 disable-on-submit">Submit</button>

            </form>
        </div>
    </div>

</div>

@endsection
<script>
document.addEventListener("DOMContentLoaded", function(){
    document.querySelector('input[list]').addEventListener('input', function(e) {
        var input = e.target,
            list = input.getAttribute('list'),
            options = document.querySelectorAll('#' + list + ' option'),
            hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden'),
            inputValue = input.value;

        hiddenInput.value = '';

        for(var i = 0; i < options.length; i++) {
            var option = options[i];
            if(option.innerText === inputValue) {
                hiddenInput.value = option.getAttribute('data-value');
                break;
            }
        }
    });
});
</script>
