@component('vendor.mail.html.message')
Hello {{$name}},  {{-- use double space for line break --}}
An issue on your property has been updated.

Please click the link below to see the update
@component('mail::button', ['url' => $link, 'color'=> 'green'])
See Issue
@endcomponent
Thanks!
@endcomponent
