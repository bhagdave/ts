@component('mail::message')
Hi team! Instead of email, whatsapp and text, let’s try using Tenancy Stream.  

To get started, make sure you...Add your profile picture.  

Say hi! Via direct messaging.  

Check out the current property we are managing.  

@component('mail::button', ['url' => $link, 'color'=> 'green'])
Sign in to Tenancy Stream
@endcomponent
@include('emails.footer')
@endcomponent
