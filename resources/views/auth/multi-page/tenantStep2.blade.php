@extends('layouts.auth.registration')
@push('authStyles')
    <link rel="stylesheet" href="https://tenancystream.com/css/header.css">
    <script type="text/javascript" src="https://tenancystream.com/js/script.js"></script>
@endpush


@section('content')
<link rel="stylesheet" href="https://tenancystream.com/css/main.css">
<link href="https://fonts.googleapis.com/css?family=Barlow:400,700|Lora" rel="stylesheet">

<div class="container-fluid" id="step3">
    <div class="row">
        <div class="col-12 form-half justify-content-center">
            <div class="card">
                <div class="card-body">
                  <div class="row justify-content-center form-steps">
                    <div class="col-4 form-steps__step form-steps__step--complete">
                      <span class="form-steps__number">1</span>
                      <span class="form-steps__copy">Email Address</span>
                    </div>
                    <div class="col-4 form-steps__step form-steps__step--complete">
                      <span class="form-steps__number">2</span>
                      <span class="form-steps__copy">Your Name</span>
                    </div>
                    <div class="col-4 form-steps__step">
                      <span class="form-steps__number">3</span>
                      <span class="form-steps__copy">Password</span>
                    </div>
                  </div>
                    <h1 class="mb-2">Your name</h1>
                    <p class="mt-0">Please enter your first and last name.</p>

                    <form method="POST" action="{{ url('/register/tenantStep2') }}">
                        @csrf

                        <div class="form-group row">
                            {{-- <label for="email" class="col-sm-12 col-form-label ">{{ __('Your name') }}</label> --}}

                            <div class="col-sm-12">
                                <input id="firstName" type="text" class="form-control @error('firstName') is-invalid @enderror" name="firstName" value="{{ old('firstName') }}" placeholder="First Name" required />
                                <input id="lastName" type="text" class="form-control @error('lastName') is-invalid @enderror" name="lastName" value="{{ old('lastName') }}" placeholder="Last Name" required />

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0 mt-3">
                            <div class="col-sm-12 ">
                                <button type="submit" class="button button--accent-bg">
                                    {{ __('Continue') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
