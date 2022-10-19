@component('vendor.mail.html.message')
Hello {{$name}},  {{-- use double space for line break --}}

We noticed you have not been on tenancy stream for a while.

Click on the link below to see if you have any updates.
@component('mail::button', ['url' => $link, 'color'=> 'green'])
Dashboard
@endcomponent
Thanks!
@endcomponent
