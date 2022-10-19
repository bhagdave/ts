@component('vendor.mail.html.message')
Hello {{$name}},  {{-- use double space for line break --}}

{{$userName}} @isset($property_address) at {{ $property_address }}@endisset has invited you to join them on Tenancy Stream

Tenancy Stream is a simple system for all your property conversations, compliance and maintenance requests. Simple. Searchable. Accessible from anywhere.

Get started  by clicking the button below.

@component('mail::button', ['url' => $link, 'color'=> 'green'])
Sign in to Tenancy Stream
@endcomponent
@include('emails.footer')
@endcomponent
