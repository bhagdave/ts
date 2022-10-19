@component('vendor.mail.html.message')
Hi,  {{-- use double space for line break --}}

You have an outstanding invite to join us on  Tenancy Stream.

Tenancy Stream is a simple system for all your property conversations, compliance and maintenance requests. Simple. Searchable. Accessible from anywhere.

To get started, register by clicking the button below.

@component('mail::button', ['url' => $link, 'color'=> 'green'])
Sign in to Tenancy Stream
@endcomponent
@include('emails.footer')
@endcomponent
