@extends('layouts.auth.registration')

@push('authStyles')
    <link rel="stylesheet" href="https://tenancystream.com/css/header.css">
    <script type="text/javascript" src="https://tenancystream.com/js/script.js"></script>
@endpush

@section('content')
<link rel="stylesheet" href="https://tenancystream.com/css/main.css">
<link href="https://fonts.googleapis.com/css?family=Barlow:400,700|Lora" rel="stylesheet">

<div class="container-fluid" id="step4">
    <div class="row">
        <div class="col-md-6 col-12 form-half justify-content-center">
            <div class="card">
                <div class="card-body">
                  <div class="row justify-content-center form-steps">
                    <div class="col-3 form-steps__step form-steps__step--complete">
                      <span class="form-steps__number">1</span>
                      <span class="form-steps__copy">Your Name</span>
                    </div>
                    <div class="col-3 form-steps__step form-steps__step--complete">
                      <span class="form-steps__number">2</span>
                      <span class="form-steps__copy">Contact Details</span>
                    </div>
                    <div class="col-3 form-steps__step form-steps__step--complete">
                      <span class="form-steps__number">3</span>
                      <span class="form-steps__copy">Trading Name</span>
                    </div>
                    <div class="col-3 form-steps__step form-steps__step--complete">
                      <span class="form-steps__number">4</span>
                      <span class="form-steps__copy">Password</span>
                    </div>
                  </div>
                    <h1 class="mb-2">Your password</h1>
                    <p class="mt-0">Please choose a unique, complex password.</p>

                    <form  method="POST" action="{{ url('/register/create') }}">
                        @csrf

                        <div class="form-group row">

                            <div class="col-sm-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror mt-3" name="password" required autocomplete="new-password" placeholder="Password">
                                <input id="password-confirm" type="password" class="form-control mt-3" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">

                                <div class="form-check mt-3">
                                  <input type="checkbox" required name="terms" class="form-check-input @error('terms') is-invalid @enderror" id="agreeTerms">
                                  <label class="form-check-label @error('terms') is-invalid @enderror" for="agreeTerms">I agree to the <a target="_blank" href="https://www.tenancystream.com/terms-conditions/">terms and conditions</a></label>
                                </div>

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
        <div class="col-md-6 col-12 app-half justify-content-center">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-4 sidebar">
                            <p class="companyName">{{ $register->companyName ?? '' }}</p>
                            <p class="name">{{ $register->firstName ?? 'name' }} {{ $register->lastName ?? 'lastname'}}</p>
                            <p class="email">{{ $register->email ?? '' }}</p>
                        </div>
                        <div class="col-lg-9 col-8">
                            <img src="{{ url('/images/dashboard.jpg') }}" alt="Dashbaord" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
