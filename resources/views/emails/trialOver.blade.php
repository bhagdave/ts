@component('vendor.mail.html.message')
Hi {{$firstName}},   

Please upgrade to our Tenancy Stream Standard subscription and keep your account live.  

Your trial has now ended and your account has had all core features limited. Nobody can reply to messages or add properties and tenants.   

To pick up where you left off, just hit the button below and upgrade now.  

@component('mail::button', ['url' => $link, 'color'=> 'green'])
Subscribe
@endcomponent

Have some questions or need assistance?    

Simply login to chat with one of our Customer Success Team, and we will be happy to help you out.


@include('emails.footer')
@endcomponent
