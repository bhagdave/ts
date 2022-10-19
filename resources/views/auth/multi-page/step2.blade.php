@extends('layouts.auth.registration')

@push('authStyles')
    <link rel="stylesheet" href="https://tenancystream.com/css/header.css">
    <script type="text/javascript" src="https://tenancystream.com/js/script.js"></script>
@endpush

@section('content')
<link rel="stylesheet" href="https://tenancystream.com/css/main.css">
<link href="https://fonts.googleapis.com/css?family=Barlow:400,700|Lora" rel="stylesheet">

<div class="container-fluid" id="step2">
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
                        <div class="col-3 form-steps__step">
                          <span class="form-steps__number">3</span>
                          <span class="form-steps__copy">Trading Name</span>
                        </div>
                        <div class="col-3 form-steps__step">
                          <span class="form-steps__number">4</span>
                          <span class="form-steps__copy">Password</span>
                        </div>
                    </div>

                    <h1>Now enter your email address and phone number</h1>
                    <p>Another step towards better property management - things get better - with every step.</p>

                    <form method="POST" action="{{ url('/register/step2') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror mt-3" name="email" value="{{ old('email') }}" placeholder="example@example.com" aria-label="email address" required autocomplete="email">
                                <input id="phone" type="tel" class="form-control @error('telephone') is-invalid @enderror mt-3" name="telephone" value="{{ old('telephone') }}" placeholder="mobile or landline" aria-label="telephone number" required autocomplete="tel">

                                @error('companyName')
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
                            <p class="email">{{ $register->firstName ?? '' }}</p>
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
</div>
@endsection
