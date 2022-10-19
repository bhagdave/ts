<div class="card">
    <div class="row d-flex">
	    <div class="col-md-6 col-sm-6">
            <ul class="nav mb-2 border-bottom border-top pb-2 pt-2">
                <li class="nav-item pl-2 pr-2">
                    <a href="/tenant/{{ $tenant->id }}/documents"><span class="oi oi-document pr-1"></span> Documents</a>
                </li>
                @if ($tenant->property)
                    <li class="nav-item pl-2 pr-2">
                        <a href="/stream/{{$tenant->property->stream_id}}"><span class="oi oi-chat pr-1"></span>Stream</a>
                    </li>
                @endif
                <li class="nav-item pl-2 pr-2">
                    <a href="/tenant/{{ $tenant->id }}/edit"><span class="oi oi-pencil pr-1"></span>Edit</a>
                </li>
                @if($tenant->user)
                    <li class="nav-item pl-2 pr-2">
                        <a href="/directmessage/tenant/{{ $tenant->id }}"><span class="oi oi-comment-square pr-1"></span>DM</a>
                    </li>
                @endif
            </ul>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Email: </strong> {{ $tenant->email ?? '' }}</li>
                    <li class="list-group-item"><strong>Phone: </strong>{{ $tenant->phone ?? '' }}</li>
                    <li class="list-group-item"><strong>Move In Date: </strong>{{  $tenant->moveInDate ?? ''  }}</li>
                    <li class="list-group-item"><strong>Rent Amount: </strong>{{ $tenant->rentAmount ?? ''  }}</li>
                    <li class="list-group-item"><strong>Rent Interval: </strong> {{ $tenant->rentDueInterval ?? ''  }}</li>
                    <li class="list-group-item"><strong>Notes: </strong> <br> {{ $tenant->notes ?? ''  }}</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6 col-sm-6">
            @if ($tenant->property)
                <iframe src = "https://maps.google.com/maps?q={{$tenant->property->propertyLat}},{{$tenant->property->propertyLng}}&hl=es;z=14&amp;output=embed" style="width:100%;height:100%;"frameborder="0"></iframe>
            @endif
        </div>        
    </div>
    <div class="card-footer text-muted">
        <span class="float-left">Added {{Carbon\Carbon::parse($tenant->created_at)->diffForHumans()}}</span>
        <form action="/tenant/{{ $tenant->id}}/delete" method="post" class="py-2">
            <input class="btn float-right btn-outline-danger btn-sm" type="submit" value="Delete Tenant" onclick="return confirm('Are you sure you want to delete this tenant?');" />
            {!! method_field('delete') !!}
            {!! csrf_field() !!}
        </form>
    </div>
</div>
   

           
