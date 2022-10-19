@component('vendor.mail.html.message')
Yesterdays Activity  

We had {{$emailData['active']}} users on the site yesterday.
{{$emailData['invitations']}} invitations created.  
{{$emailData['streams']}} streams created.  
{{$emailData['tenants']}} tenants created.  
{{$emailData['messages']}} messages created.  
{{$emailData['agents']}} agents created.  
{{$emailData['usersCreated']}} users created.  
{{$emailData['landlords']}} landlords created.  
{{$emailData['properties']}} properties created.  
{{$emailData['documents']}} documents created.  
{{$emailData['issues']}} issues created.  

Users who registered on the platform yesterday  

@foreach($emailData['usersRegistered'] as $registered)
    {{$registered->type}} {{$registered->usersCreated}}  

@endforeach

Retention Numbers:-  
1 day on from account creation:{{$emailData['retentionRates']['oneday']}}  
2 day on from account creation:{{$emailData['retentionRates']['twoday']}}  
1 week on from account creation:{{$emailData['retentionRates']['oneweek']}}  
2 week on from account creation:{{$emailData['retentionRates']['twoweek']}}  
1 month on from account creation:{{$emailData['retentionRates']['onemonth']}}  
2 month on from account creation:{{$emailData['retentionRates']['twomonth']}}  

Breakdown of users on the platform:  

@foreach($emailData['userSplit'] as $user)
    {{$user->userType}} {{$user->number}}  
@endforeach


Agencies with trial ending in 3 days:-
@foreach($emailData['agencies'] as $agency)
    {{$agency->company_name}} {{$agency->phone}}  {{$agency->mainAgent()->user->telephone}} {{$agency->mainAgent()->user->email}}  
@endforeach
@endcomponent
