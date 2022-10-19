@component('vendor.mail.html.message')
Hi,  {{-- use double space for line break --}}

The contractor {{$contractorName}} has requested to work on one of your open issues.  

The issue is at {{$propertyAddress}} and has been described as {{$description}}.  

You can follow the link below to accept the request or you can contact the contractor on {{$contractorEmail ?? 'No email supplied'}}.  

Tenancy Stream is a simple system for all your property conversations, compliance and maintenance requests. Simple. Searchable. Accessible from anywhere.


@component('mail::button', ['url' => $link, 'color'=> 'green'])
Sign in to Tenancy Stream
@endcomponent
@include('emails.footer')
@endcomponent
