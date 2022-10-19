


<div class="list-group-item list-group-item-action bg-light align-self-end border-0 d-flex sidebarFooter" style="">
    <div>
        <img src="{{$user->profileImage ?? "/images/default.png"}}" class="rounded-circle mr-3" width="40" height="40">
    </div>
    <div>
        <strong class='userName'>{{$user->firstName ?: "Welcome"}} {{$user->lastName ?: ""}}</strong>  <br>
        <a href="/profile/edit" class="pr-2">Profile</a>
        @if(($user->agent) && ($user->agent->main))
            <a href="/agency/admin" class="pr-2">Admin</a>
        @endif
        <div id="sub" style="display:none">
            {{$user->sub ?: "None"}}
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>

<div class="sidebar-heading">


    Notifications

    <a class="text-secondary" id="notifications" href="/notifications/">
        <span class="oi oi-bell float-right mt-2"></span>
    </a>
    <div id="notificationsarea" class="float-right pt-1">
    </div>
    <div class='onesignal-customlink-container'></div>
</div>
