@extends('layouts.app', ['mainClassOff' => true])
@section('content')

<div class="container streamShow">
    <div class="row flex-column-reverse flex-md-row">
        <div class="col-md-12 bg-white rounded shadow-sm">
            <div id="streamInfo" class="border-bottom border-gray pt-3 mb-0 d-flex align-items-center justify-content-between" style="height: 8vh !important;">
                    <div class="col-auto">
                        @if ($otherUser->userType == 'Tenant')
                            <a href="/tenant/{{$otherUser->tenant->id}}">
                                <img src="{{$otherUser->profileImage ?? '/images/default.png' }}" class="rounded-circle border-0" width="40" height="40">
                            <a>
                        @else
                            <img src="{{$otherUser->profileImage ?? '/images/default.png' }}" class="rounded-circle border-0" width="40" height="40">
                        @endif
                    </div>
                    <span>
                        Direct Messages with {{$otherUser->firstName }} {{$otherUser->lastName}}({{ $otherUser->userType }}) 
                        @if(isset($property))
                             at <a href="/property/{{$property->id}}">{{$property->propertyName}}</a> 
                        @endif
                    </span>
            </div>
            <direct-messages ref="directMessage" v-bind:messages="{{ json_encode($messages)  }}"  :subscribed="'{{ $user->subscribed }}'" :recipient="'{{ $otherUser->sub }}'" :user="'{{ $user->sub }}'" :recipientname="'{{ $otherUser->firstName }}'"></direct-messages>
        </div>
    </div>
</div>
@endsection
