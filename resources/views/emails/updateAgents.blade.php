@component('vendor.mail.html.message')
Hello {{$name}}, 

{{$message}}  

@component('mail::button', ['url' => $link, 'color'=> 'green'])
Sign in to Tenancy Stream to see the new changes  
@endcomponent
@include('emails.footer')
@endcomponent
