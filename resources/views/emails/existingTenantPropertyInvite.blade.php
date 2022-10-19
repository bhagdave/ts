@component('vendor.mail.html.message')
Hello {{$name}},  {{-- use double space for line break --}}

{{$agentName}} @if(isset($agencyName)) from {{$agencyName}} @endif tried to  add you to a property on Tenancy Stream but was not able to because you are alredy attached to a property.  Please contact your existing agent on Tenancy Stream and ask them to remove you from your existing property.


@component('mail::button', ['url' => 'https://www.tenancystream.app', 'color'=> 'green'])
Sign in to Tenancy Stream
@endcomponent
@include('emails.footer')
@endcomponent
