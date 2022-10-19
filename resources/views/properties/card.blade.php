<div class="card border-right border-bottom-0 border-top-0 border-left-0 border-success">
    <div class="row d-flex">
        @include('properties/nav')
        <div class="col-md-6 col-sm-12">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item border-top-0 pl-2"><strong>Address 1: </strong> {{ $property->inputAddress }}</li>
                    @if($property->inputCity )
                        <li class="list-group-item pl-2"><strong>City: </strong>{{ $property->inputCity }}</li>
                    @endif
                    @if($property->inputPostCode )
                        <li class="list-group-item pl-2"><strong>Postcode: </strong>{{ $property->inputPostCode }}</li>
                    @endif
                    @if ($user->userType != "Tenant")
                        @if($property->propertyNotes )
                            <li class="list-group-item pl-2"><strong>Notes: </strong><br>{{ $property->propertyNotes }}</li>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <iframe src = "https://maps.google.com/maps?q={{$property->propertyLat}},{{$property->propertyLng}}&hl=es;z=14&amp;output=embed" style="width:98%;height:100%;"frameborder="0"></iframe>
        </div>
    </div>
    <div class="card-footer text-muted">
        <span class="float-left">Added {{Carbon\Carbon::parse($property->created_at)->diffForHumans()}}</span>
         @if ($user->userType != "Tenant")
            <form action="/property/{{ $property->id}}/delete" method="post">
                <input class="btn btn-outline-danger btn-sm float-right" type="submit" value="Delete Property" onclick="return confirm('Are you sure you want to delete this item? All tenants and issues will be removed.');" />
                    {!! method_field('delete') !!}
                    {!! csrf_field() !!}
            </form>
            @if (count($property->tenants) == 0)
                <a href="/tenant/create?property_id={{$property->id}}" class="mr-2 pr-2 btn btn-outline-primary btn-sm float-right">Invite Tenant</a>
            @endif
            <a href="/property/rent/{{$property->id}}" class="mr-2 pr-2 btn btn-outline-primary btn-sm float-right">Rent Payments</a>
        @endif
    </div>
</div>

