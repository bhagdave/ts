<div class="list-group-item list-group-item-action bg-light align-self-end border-0 d-flex sidebarFooter" style="">
    <div>
        <img src="{{$user->profileImage ?? "/images/default.png"}}" class="rounded-circle mr-3" width="40" height="40">
    </div>
    <div>
        <strong class='userName'>{{$user->firstName ?: "Welcome"}} {{$user->lastName ?: ""}}</strong>  <br>
        <a href="/profile/edit" class="pr-2">Profile</a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
        </a>

        <div id="sub" style="display:none">
            {{$user->sub ?: "None"}}
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>
