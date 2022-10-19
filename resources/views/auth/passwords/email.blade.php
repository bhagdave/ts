@extends('layouts.app')
@section('content')

<style>
body{background:white !important;font-family: "Barlow", serif !important;}


</style>
<link rel="stylesheet" href="https://tenancystream.com/css/main.css">
<link href="https://fonts.googleapis.com/css?family=Barlow:400,700|Lora" rel="stylesheet">

<header class="row h-100" id="header">
  <div class="col-md-6 min-vh-100">
    <div class="">
      <div class="p-5">
        <h3 class="pt-2">Forgot your password?</h3>
        <p>You can reset it here.</p>
        <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-12 col-form-label">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    </form>
      </div>
    </div>
        </div>
<div class="col-md-6 bg-light min-vh-100 d-none d-md-block" style="background-image:url('https://images.unsplash.com/photo-1510265236892-329bfd7de7a1?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=700&q=100');background-size:cover;background-repeat:no-repeat">

</div>
  </div>
</header>

