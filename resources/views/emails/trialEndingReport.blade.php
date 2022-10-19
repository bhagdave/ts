@component('vendor.mail.html.message')
Agencies with trial ending in the next 14 days:-
@foreach($agencies as $agency)
    {{$agency->company_name}} {{$agency->phone}}  {{$agency->firstName . ' ' . $agency->lastName}} {{$agency->email}} {{$agency->trial_ends_at}}  
@endforeach
@endcomponent
