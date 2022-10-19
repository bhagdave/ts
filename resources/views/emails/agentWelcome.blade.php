@component('vendor.mail.html.message')
A new {{$type}} user has registered on  Tenancy Stream! Their details are below.  {{-- use double space for line break --}}

Agency Name: {{$companyName}},  {{-- use double space for line break --}}
Email: {{$email}}  {{-- use double space for line break --}}
Telephone: {{$phone ?? ''}}  

Thanks!  {{-- use double space for line break --}}

Dave
@endcomponent
