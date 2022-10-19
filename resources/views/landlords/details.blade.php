<div class="card">
    <div class="row d-flex">
        <div class="col-md-6 col-sm-12">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Email: </strong> {{ $landlord->email }}</li>
                    <li class="list-group-item"><strong>Phone: </strong>{{ $landlord->phone }}</li> 
                    <li class="list-group-item"><strong>Notes: </strong> <br> {{ $landlord->notes }}</li>
                </ul>
                <div class='py-3'>
                    <a href="/landlord/{{ $landlord->id }}/edit" class="btn btn-outline-primary btn-sm">
                        Edit landlord
                    </a>
                    <a href="/directmessage/landlord/{{ $landlord->id }}" class="btn btn-outline-primary btn-sm">
                        Message
                    </a>
                    <a href="/landlord/{{ $landlord->id }}/report" class="btn btn-outline-primary btn-sm">
                        Monthly Report
                    </a>
                    <form action="/landlord/{{ $landlord->id}}/delete" style="display:inline;" method="post" class="py-2">
                        <input class="btn btn-outline-danger btn-sm" type="submit" value="Delete landlord" onclick="return confirm('Are you sure you want to delete this landlord? Properties will remain in your agent account and must be deleted seperately.');" />
                        {!! method_field('delete') !!}
                        {!! csrf_field() !!}
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-muted">
        Added {{Carbon\Carbon::parse($landlord->created_at)->diffForHumans()}}
    </div>
</div>
