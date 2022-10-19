@extends('layouts.app')
@section('content')
<main role="main" class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Admin</h3>
			</div>
		</div>
	</div>
    @include('agents/menu')
    <hr>
    <div class="row">
        @isset($billing)
            <div class="col-sm-12 col-md-12 align-self-center">
                <h4>Billing &amp; Plans</h4>
                
                @if ($billing['subscribed'])
                    <h6 class="text-muted mt-2">To modify your billing and view your payment details, <a href="{{$billing['url']}}">Billing Portal</a></h6>
                    <strong>Standard Account </strong>
                @else
                    @if ($billing['founder'])
                        <div>
                            <p>You are a founder member and will not have to pay for the service.</p>
                        </diV>
                    @else
                        <div>
                            <p>Your free trial subscription ends on {{ $billing['trialEnds'] }} but don't worry if you've loved using Tenancy Stream you just need to follow the link below and you won't lose any of the data - or hard work - you've put in so far</p>
                            <a class="btn btn-primary" href="/payment">Subsrcibe</a>
                        </diV>
                    @endif
                @endif
            </div>
        @endisset
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-12 col-md-12 align-self-center">
            <h4> Reminders </h4>
            <a href="/reminders/manage">Manage all reminders</a>
        </div>
    </div>
    <hr>
</main>
@endsection
