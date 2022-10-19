@component('vendor.mail.html.message')
Hello {{$name}},  {{-- use double space for line break --}}

Join {{$agentName}} @if(isset($agencyName)) from {{$agencyName}} @endif on Tenancy Stream

Tenancy Stream is a simple system for all your property conversations, compliance and maintenance requests. Simple. Searchable. Accessible from anywhere.

To get started, sign in to your property {{$property_address}} by clicking the button below.

@component('mail::button', ['url' => $link, 'color'=> 'green'])
Sign in to Tenancy Stream
@endcomponent
@include('emails.footer')
@endcomponent
