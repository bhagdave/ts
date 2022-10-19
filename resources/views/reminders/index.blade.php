@extends('layouts.app')
@section('content')
    <main role="main" class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h3>Reminder Calendar</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="border-right border-info border-top-0 border-bottom-0  bg-white rounded shadow-sm col-sm-12">
                @if( !$user->subscribed )
                    <p class="pb-3">
                        Your trial has come to and end and you have not subscribed so will not be able to view reminders.  
                        Please <a href="{{url('payment')}}">click here</a> to subscribe.
                    </p>
                @else
                    <a href="/reminders/manage" class="btn btn-outline-primary">Manage Reminders</a>
                    <reminders :usertype="'{{$user->userType}}'" :reminders="{{$reminders}}"></reminders>
                @endif`
            </div>
        </div>
    </main>
@endsection
