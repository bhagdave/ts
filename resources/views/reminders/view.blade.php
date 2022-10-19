@extends('layouts.app')
@section('content')

<main role="main" class="container">
	<div class="row justify-content-md-center">
		<div class="col-lg-12 col-md-6 bg-white rounde dalign-self-center">
            @include('reminders/' . $type)
            @if( !$user->subscribed )
                <p class="pb-3">
                    Your trial has come to and end and you have not subscribed so will not be able to view reminders.  
                    Please <a href="{{url('payment')}}">click here</a> to subscribe.
                </p>
            @else
                <a href="/reminders/manage/{{$type}}/{{$typeId}}" class="btn btn-outline-primary">Manage Reminders</a>
                <reminders :usertype="'{{$user->userType}}'" :type="'{{$type}}'" :typeid="'{{$typeId}}'" :reminders="{{$reminders}}"></reminders>
            @endif
		</div>

	</div>
</main>
@endsection
