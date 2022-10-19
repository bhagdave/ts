@component('vendor.mail.html.message')
Hello {{$name}},  {{-- use double space for line break --}}

You have a new message in the property stream where you live.

Please click the link below to see the update
@component('mail::button', ['url' => $link, 'color'=> 'green'])
See Message
@endcomponent
Thanks!
@endcomponent
