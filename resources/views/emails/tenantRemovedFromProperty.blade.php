@component('vendor.mail.html.message')
Hello {{$name}},  {{-- use double space for line break --}}

Your agent or landlord has removed you from the property ({{$address}}) managed on Tenancy Stream.

No need to worry though as you can still use Tenancy Stream.  Just login following the link below and you can add wherever you currently live and then add your new landlord or agent.

@component('mail::button', ['url' => "https://www.tenancystream.app", 'color'=> 'green'])
Dashboard
@endcomponent
Thanks!
@endcomponent
