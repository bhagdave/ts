@component('vendor.mail.html.message')
Hi,  {{-- use double space for line break --}}

You have been assigned to work on an issue on Tenancy Stream by {{$agentName}} @if(isset($agencyName)) from {{$agencyName}} @endif.  

The issue is at {{$propertyAddress}} and has been described as {{$description}}.  Just follow the link below to get to the issue.

Tenancy Stream is a simple system for all your property conversations, compliance and maintenance requests. Simple. Searchable. Accessible from anywhere.


@component('mail::button', ['url' => $link, 'color'=> 'green'])
Sign in to Tenancy Stream
@endcomponent
@include('emails.footer')
@endcomponent
