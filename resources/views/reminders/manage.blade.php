@extends('layouts.app')
@section('content')
    <main role="main" class="container">
        @include('reminders/' . $type, ['manage' => true])
        @if( !$user->subscribed )
            <p class="pb-3">
                Your trial has come to and end and you have not subscribed so will not be able to manage reminders.  
                Please <a href="{{url('payment')}}">click here</a> to subscribe.
            </p>
        @else
            <small class="text-muted">Please note amending a main Reminder may impact all recurrences of that event</small>
            <br />
            @if(isset($model))
                <a href="/reminders/create/{{$type}}/{{$typeId}}" class="add-reminder btn btn-outline-primary">Add Reminder</a>
                <a href="/reminders/view/{{$type}}/{{$typeId}}" class="btn btn-outline-primary">View Reminders</a>
            @endif
            <div class="row">
                <div class="col-sm-12">
                    @include('reminders.table')
                </div>
            </div>
        @endif
    </main>
@endsection
