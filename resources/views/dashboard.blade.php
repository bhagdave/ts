@extends('layouts.app')
@section('content')
    @if (null !== session('wizard'))
        @if (session('wizard') == 'document')
            <document-wizard></document-wizard>
        @endif
        @if (session('wizard') == 'reminder')
            <reminder-wizard></reminder-wizard>
        @endif
        @if (session('wizard') == 'stream')
            <stream-wizard></stream-wizard>
        @endif
        @if (session('wizard') == 'property')
            <property-wizard></property-wizard>
        @endif
    @endif
    <main role="main" class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h3>
                        @if ($user->userType=="Agent" )
                            {{$user->agent->name ?? ''}}
                        @endif
                        Dashboard
                    </h3>
                    @if ( $user->userType == "Agent" )
                        @if ( $user->agent->agency->founder )
                        @else
                            @if ( !$user->subscribed )
                                <div class="card text-center">
                                    <div class="card-header">
                                        Subscription
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">Your trial ended and you now have restricted access </p>
                                        <a href="/payment" class="card-link">Please Subscribe</a>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @if ( $user->userType == "Agent")
            @if ( $user->agent->agency->onGenericTrial() )
                <div class="row pt-2">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header text-center">
                                Hints & Tips
                            </div>
                            <div class="card-body">
                                {!! $hint ?? "These hints and tips will appear until the end of the trial period to help you to get you and your team onboard" !!}
                                <hr>
                                <p class="text-center card-text">Trial ends: {{ \Carbon\Carbon::parse($user->agent->agency->trial_ends_at)->format('j F, Y')  }}
                                <a href="/payment" class="card-text text-center card-link">(Subscribe Here)</a></p>
                                These wizards will help you to do some of the task you need to do to make the most of the Tenancy Stream Platform.  They work best on a Laptop/Desktop and will appear for the duration of your trial.
                                <hr>
                                <a href="/?wizard=document" class="card-text text-center card-link">Adding documents</a>
                                <a href="/?wizard=reminder" class="card-text text-center card-link">Adding reminders</a>
                                <a href="/?wizard=stream" class="card-text text-center card-link">Sending Messages</a>
                                <a href="/?wizard=property" class="card-text text-center card-link">Add a property</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
        @if ($user->userType=="Agent" || $user->userType == "Landlord")
            <div class="row">
                <div class="col-sm-6">
                    <user-activities ref="activities"></user-activities>
                    <tenant-count></tenant-count>
                    <document-count></document-count>
                </div>
                <div class="col-sm-6">
                    @include('dashboard/quicktasks')
                    @include('dashboard/counts')
                    @include('dashboard/reminders')
                    @include('dashboard/signable')
                    @if($user->newsfeed)
                        @include('dashboard.news')
                    @endif
                </div>
            </div> 
        @endif
        @if ($user->userType=="Tenant")
            <div class="row">
                @include('tenants.dashboard')
            </div>
        @endif
        @if ($user->userType=="Contractor")
            <div class="row">
                <div class="col-sm-12">
                    @include('contractors/openissues')
                </div>
            </div> 
        @endif
</main>
@endsection
