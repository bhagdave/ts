@component('vendor.mail.html.message')
Hello {{$name}},  {{-- use double space for line break --}}

You have some unread messages on Tenancy Stream.

Click on the link below to check your unread messages.
@component('mail::button', ['url' => $link, 'color'=> 'green'])
Dashboard
@endcomponent
Thanks!
@endcomponent
