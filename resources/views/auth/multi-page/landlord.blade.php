@extends('layouts.auth.registration')

@push('authStyles')
    <link rel="stylesheet" href="https://tenancystream.com/css/header.css">
    <script type="text/javascript" src="https://tenancystream.com/js/script.js"></script>
@endpush

@section('content')
<link rel="stylesheet" href="https://tenancystream.com/css/main.css">
<link href="https://fonts.googleapis.com/css?family=Barlow:400,700|Lora" rel="stylesheet">

<div class="container" id="step1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 form align-self-center p-5">
          <div class="row justify-content-center form-steps">
            <div class="col-4 form-steps__step form-steps__step--complete">
              <span class="form-steps__number">1</span>
              <span class="form-steps__copy">Email Address</span>
            </div>
            <div class="col-4 form-steps__step">
              <span class="form-steps__number">2</span>
              <span class="form-steps__copy">Your Name</span>
            </div>
            <div class="col-4 form-steps__step">
              <span class="form-steps__number">3</span>
              <span class="form-steps__copy">Password</span>
            </div>
          </div>

            <h1>First, enter your email address</h1>
            <p>Just the first step towards better property management - things get better - with every step.</p>
            <form method="POST" action="{{ url('/register/landlord') }}">
                @csrf

                <div class="form-group row">

                    <div class="col-sm-12">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="example@example.com" aria-label="email address" required autocomplete="email">

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
                            {{ __('Get Started As A Landlord ') }}
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
