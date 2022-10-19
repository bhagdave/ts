@component('vendor.mail.html.message')
Hi {{$firstName}},  {{-- use double space for line break --}}

We noticed that you had cancelled your subscription to Tenancy Stream.  

We would hate to see you go and lose all of the work that you have put into the platform so far.    

Please follow the link below to complete your subscription.  

@component('mail::button', ['url' => $link, 'color'=> 'green'])
Sign in to Tenancy Stream
@endcomponent
@include('emails.footer')
@endcomponent
