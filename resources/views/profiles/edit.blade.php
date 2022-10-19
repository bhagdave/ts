@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Profile</h3>
				<a class="btn btn-outline-primary btn" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
						{{ __('Logout') }}
				</a>
			</div>
        </div>
    </div>
</div>
<main role="main" class="container">
	<div class="row pt-3 justify-content-md-center">
		<div class="col-sm-12 col-md-12 align-self-center">
			<div class="">
				<form action="/profile/update" method="post" enctype="multipart/form-data">
					{!! csrf_field() !!}

					<div class="question question-2 py-3">
						<h4>Update Personal Information</h4>

						<h6 class="text-muted mt-2">Your name will be used on the Stream to help people recognise you.</h6>
						<div class="form-row pt-3">
							<div class="form-group col">
								<label for="firstName">First Name</label>
								<input type="text" class="form-control" id="firstName" name="firstName" placeholder="Sam" value="{{$user->firstName}}" required>
							</div>
							<div class="form-group col">
								<label for="lastName">Last Name</label>
								<input type="text" class="form-control" id="lastName" name="lastName" placeholder="Lastname" value="{{$user->lastName}}" required>
							</div>
                        </div>
						<div class="form-row pt-3">
							<div class="form-group col-4">
								<label for="telephone">Telephone</label>
								<input type="text" class="form-control" id="telephone" name="telephone" placeholder="Mobile or Landline number" value="{{$user->telephone}}" >
							</div>
						</div>
                        @if($user->userType == 'Agent')
						<div class="form-row pt-3">
							<div class="form-group col">
								<label for="property_count">Property Count</label>
								<input type="number" class="form-control" id="property_count" name="property_count" placeholder="10" value="{{$user->agent->property_count ?? ''}}">
							</div>
							<div class="form-group col">
                                @include('partials.countries', ['country' => $user->agent->country])
							</div>
                        </div>
                        @endif
						<h6 class="text-muted mt-2">Please contact us to modify other information.</h6>

					</div>

					<div class="question question-3 py-3">
						<h4>Change Picture</h4>
						<h6 class="text-muted mt-2">Your picture will be used on the Stream to help people recognise you.</h6>
						<div class="form-row py-3">
							<div class="input-group pt-3 col">
								<input id="profileImage" type="file" class="form-control" name="profileImage" value="{{$user->profileImage}}">
							</div>
						</div>
					</div>
                    @if($user->userType == 'Contractor')
                        <h4>Categories of work you cover</h4>
                        <div class="col-sm-12">
                            <select name="categories[]" class="categories-selector"  multiple="multiple">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @if(in_array($category->id , $selectedCategories)) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>

                            @error('categories')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @endif
					<div class="question question-3 py-3">
						<h4>Email notifications</h4>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="notifications" id="notifications-on" value="true" @if($user->email_notifications) checked @endif >
                            <label class="form-check-label" for="notifications">Receive email notifications</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="notifications" id="notifications-off" value="false" @if(!$user->email_notifications) checked @endif >
                            <label class="form-check-label" for="notifications">DO NOT receive email notifications</label>
                        </div>
                    </div>
					<div class="question question-3 py-3">
						<h4>Latest News on Dashboard</h4>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="newsfeed" id="newsfeed-on" value="true" @if($user->newsfeed) checked @endif >
                            <label class="form-check-label" for="newsfeed">Show Newsfeed on Dashboard</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="newsfeed" id="newsfeed-off" value="false" @if(!$user->newsfeed) checked @endif >
                            <label class="form-check-label" for="newsfeed">DO NOT Show Newsfeed on Dashboard</label>
                        </div>
                        <div class="submit py-3">
                            <button type="submit" class="btn btn-primary btn-lg">Submit </button>
                        </div>
                    </div>
				</form>
			</div>
		</div>
    </div>
    @if($user->userType == 'Agent')
        <hr>
        <div class="row pt-3 justify-content-md-center">
            <div class="col-sm-12 col-md-12 align-self-center pt-3">
                    <h4>Invite a new user to your agency.</h4>
                    <a href="/invite/user" class="btn btn-primary">Invite User</a>
            </div>
        </div>
    @endif

</main>
<script>
$( document ).ready(function() {
    $('option').mousedown(function(e) {
        e.preventDefault();
        $(this).prop('selected', !$(this).prop('selected'));
        return false;
    });
});
</script>
@endsection
