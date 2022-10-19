@extends('layouts.app')
@section('content')
<main role="main" class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
                <h3>Subscribe</h3>
			</div>
			<hr>
		</div>
	</div>
	<div class="row pt-3 justify-content-md-center">
		<div class="col-sm-12 col-md-12 align-self-center">
			<div class="">
                <form method="post" id="payment-form">
                    {!! csrf_field() !!}
                    @if (isset($paymentMethod))
                        <div class="card text-center">
                            <div class="card-body">
                                <p class="card-text">Your free trial subscription has ended but don't worry if you've loved using Tenancy Stream you just need to click on the subscribe button below and you won't lose any of the data - or hard work - you've put in so far</p>
                            </div>
                        </div>
                        <input type="hidden" id="payment_method" value="{{$paymentMethod}}" name="payment_method">
                        <input type="hidden" id="plan" value="{{$planId}}" name="plan">
                        <button class="btn btn-primary" id="card-button"> 
                            Subscribe
                        </button>
                    @else
                        <div class="card text-center">
                            <div class="card-body">
                                <p class="card-text">Your free trial subscription has ended but don't worry if you've loved using Tenancy Stream you just need to fill in your payment details and click on the subscribe button below and you won't lose any of the data - or hard work - you've put in so far</p>
                            </div>
                        </div>
                        <input type="hidden" id="payment_method"  name="payment_method">
                        <input type="hidden" id="plan" value="{{$planId}}" name="plan">
                        <input class="form-control mt-3" required placeholder="Card Holder Name" id="card-holder-name" type="text">

                        <!-- Stripe Elements Placeholder -->
                        <div class="form-control mt-3" id="card-element"></div>

                        <button class="btn btn-primary mt-3" id="card-button" data-secret="{{ $intent->client_secret }}">
                            Subscribe
                        </button>
                    @endif
                </form>
			</div>
		</div>
        <div id="LoadingDiv" style="display: none;">
            <div>
                <img src="images/loader.gif" title="Loading" />
                <div class="LoadingTitle">Subscribing...</div>
            </div>
        </div>
    </div>
</main>
<script src="https://js.stripe.com/v3/"></script>
<script>
</script>

<script>
    $( document ).ready(function() {
        const stripe = Stripe('{{$stripeKey}}');

        const elements = stripe.elements();
        const cardElement = elements.create('card');

        cardElement.mount('#card-element');
        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;

        cardButton.addEventListener('click', async (e) => {
            cardButton.disabled = true;
            $('#LoadingDiv').show();
            e.preventDefault();
            const { setupIntent, error } = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: { name: cardHolderName.value }
                    }
                }
            );
            if (error){
                alert('Unable to create subscription. Please try again.');
                cardButton.disabled = false;
                $('#LoadingDiv').hide();
            } else {
                $('#payment_method').val(setupIntent.payment_method);
                $('#payment-form').submit();
            }
        });
    });
</script>
@endsection
