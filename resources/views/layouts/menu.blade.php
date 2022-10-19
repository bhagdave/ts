<a href="/" class="border-bottom-0 border-top-0 border-right border-primary list-group-item list-group-item-action w-100"><span class="text-primary oi oi-compass pr-2"></span> Dashboard</a>
<unread-messages :messages="unread" :streams="unreadStreams"></unread-messages>
<a href="/issues" class="border-right border-top-0 border-bottom-0 border-danger list-group-item list-group-item-action"><span class="text-danger oi oi-task pr-2"></span> Issues</a>

@if (!$user->isAn('contractor'))
    <stream-menu  :streams="streams"></stream-menu>
@endif

@if ($user->isAn('tenant'))
    @if(isset($user->tenant))
        @if (!isset($tenantNeedsToInvite) || !$tenantNeedsToInvite)
            <a href="/yourDocuments" class="border-right border-top-0 border-bottom-0 border-danger list-group-item list-group-item-action"><span class="text-info oi oi-document pr-2"></span> Documents</a>
            <tenants-dm></tenants-dm>
        @endif
    @endif
@endif

@if ($user->isAn('agent')  || $user->isAn('landlord'))
    <a href="/property/create" class="property-wizard border-right border-success border-top-0 border-bottom-0 list-group-item list-group-item-action"><span class="text-success oi oi-plus pr-2"></span> Add Property</a>
    <a href="/tenants" class="border-right border-warning border-top-0 border-bottom-0 list-group-item list-group-item-action"><span class="text-warning oi oi-people pr-2"></span> Tenants</a>
@endif

@if ($user->isAn('agent'))
    <a href="/landlords" class="border-right border-warning border-top-0 border-bottom-0 list-group-item list-group-item-action"><span class="text-warning oi oi-person pr-2"></span> Landlords</a>
    <a href="/invite/user" class="border-right border-warning border-top-0 border-bottom-0 list-group-item list-group-item-action"><span class="text-warning oi oi-plus pr-2"></span>Invite Colleague</a>
    <a href="/documents" class="doc-wizard border-right border-info border-top-0 border-bottom-0 list-group-item list-group-item-action"><span class="text-info oi oi-folder pr-2"></span> Documents</a>
@endif

@if ($user->userType=='Admin')
  <a href="/admin" class="list-group-item list-group-item-action"><span class="oi oi-globe pr-2"></span> Admin</a>
@endif

@if ($user->isAn('agent'))
    <a class="reminder-wizard border-right border-info border-top-0 border-bottom-0 list-group-item list-group-item-action" href="/reminders"><span class="text-info oi oi-calendar pr-2"></span>Reminders</a>
    <colleague-menu></colleague-menu>
@endif

@if ($user->isAn('landlord'))
    <landlords-dm></landlords-dm>
@endif

@if(isset($activity))
    @foreach($activity as $menuitem)
        @if(isset($menuItem->propertyName))
            <a class="chat-button list-group-item list-group-item-action" href="/stream/{{ $menuitem->log_name  }}"><span class="oi oi-chat pr-2"></span><strong> {{ $menuitem->propertyName }}</strong></a>
        @endif
    @endforeach
@endif
<li class="border-right border-info border-top-0 border-bottom-0 list-group-item" data-toggle="collapse"  data-target="#help"><span class="oi oi-question-mark pr-2"></span>Help</li>
<ul class=" list-group collapse" id="help">
    <a class="chat-button list-group-item list-group-item-action" href="#"> <span class="oi oi-bullhorn pr-2"></span>Chat</a>
    <a class="list-group-item list-group-item-action" href="/?wizard=document"> <span class="oi oi-book pr-2"></span>Adding Documents</a>
    <a class="list-group-item list-group-item-action" href="/?wizard=reminder"> <span class="oi oi-book pr-2"></span>Adding Reminders</a>
    <a class="list-group-item list-group-item-action" href="/?wizard=stream"> <span class="oi oi-book pr-2"></span>Sending Messages</a>
</ul>
