@component('vendor.mail.html.message')
Hello,  {{-- use double space for line break --}}
One of your tenants has reported an issue on a property.

Please click the link below to see the issue
@component('mail::button', ['url' => $link, 'color'=> 'green'])
See Issue
@endcomponent
Thanks!
@endcomponent
