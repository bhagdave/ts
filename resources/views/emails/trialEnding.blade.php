@component('vendor.mail.html.message')
Hi {{$firstName}},    

I wanted to let you know that in 3 days some of the core features on your account will be limited and your message history will be unavailable. You have {{ $storedMessages }} messages stored in your Tenancy Stream account.  

To pick up where you left off, just hit the button below and upgrade now.  

@component('mail::button', ['url' => $link, 'color'=> 'green'])
Subscribe
@endcomponent
@include('emails.footer')
@endcomponent
