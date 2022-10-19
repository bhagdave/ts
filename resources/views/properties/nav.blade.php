<ul class="nav mb-2 border-bottom border-top pb-2 pt-2">
    @if (isset($property))
        <li class="nav-item pl-4 pr-2">
            <a href="/propertyissues/{{ $property->id}}" ><span class="oi oi-task  pr-1"></span>{{count($property->issues)}} Issue/s</a>
        </li>
        <li class="nav-item pr-2">
            <a href="/property/{{ $property->id  }}/documents"><span class="oi oi-document pr-1"></span> Documents</a>
        </li>
        @if (!isset($stream))
            <li class="nav-item pr-2">
                <a href="/stream/{{ $property->stream_id  }}"><span class="oi oi-chat pr-1"></span>Stream</a>
            </li>
        @endif
    @endif
    @if ($user->userType != "Tenant")
        @if (isset($property->private_stream_id) && $user->userType == "Agent")
            <li class="nav-item pr-2">
                <a href="/stream/{{ $property->private_stream_id  }}"><span class="oi oi-chat pr-1"></span>Private Stream</a>
            </li>
        @endif
        <li class="nav-item pr-2 pl-2">
            <a href="/tenants/{{ $property->id}}" ><span class="oi oi-people pr-1"></span>{{count($property->tenants)}} Tenant/s</a>
        </li>
        <li class="nav-item pr-2">
            <a href="/property/{{ $property->id}}/edit" ><span class="oi oi-pencil  pr-1"> </span> Edit</a>
        </li>
        <li class="nav-item  pr-2">
            <a href="/property/rent/{{ $property->id}}" ><span class="oi oi-british-pound  pr-1"> </span>Rent</a>
        </li>
        <li class="nav-item pr-2">
            <a href="/reminders/view/property/{{ $property->id}}" ><span class="oi oi-calendar pr-1"></span>Reminders</a>
            <a href="/reminders/manage/property/{{ $property->id}}" >(Edit)</a>
            -
            <a class="add-reminder" href="/reminders/create/property/{{ $property->id}}" >(Add)</a>
        </li>
    @endif
</ul>

