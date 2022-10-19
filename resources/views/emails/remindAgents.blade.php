@component('vendor.mail.html.message')
Hello, 

You have the following reminders for today:-  
@foreach($reminders as $reminder)
{{$reminder->name}}  
@endforeach

@component('mail::button', ['url' => url('/'), 'color'=> 'green'])
Sign in to Tenancy Stream to check your reminders    
@endcomponent
@include('emails.footer')
@endcomponent
