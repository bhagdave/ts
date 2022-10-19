<div class="my-3 p-3 bg-white border-right border-danger rounded shadow-sm">
    <div class="d-flex align-items-center justify-content-between">
        <h4>Issues</h4>
    </div>
    <div class="d-flex ">
        <a class="btn btn-sm btn-outline-primary" href="/issues" role="button">
            <span class="oi oi oi-task"></span> See Issues
        </a>
        @can('createIssue')
            <a href="/issues/create" class="btn btn-sm float-right btn-outline-primary" role="button"><span class="oi oi-plus"></span> New Issue</a>
        @endcan
    </div>
    <div class="d-flex">
        <a class="text-center btn btn-outline-primary btn-block mt-2 mr-1" href="/issues?filter=Open">
            <h3>{{$countOpen ?? '?'}}</h3><small>Open</small>
        </a>
        <a class="btn btn-outline-primary text-center btn-block mr-1" href="/issues?filter=Pending Landlord">
            <h3>{{$countPendingLandlord ?? '?'}}</h3><small>Pending Landlord</small>
        </a>
            <a class="text-center   btn btn-outline-primary btn-block mr-1" href="/issues?filter=Pending Contractor">
                <h3>{{$countPendingContractor ?? '?'}}</h3><small>Pending Contractor</small>
            </a>
            <a class="text-center   btn btn-outline-primary btn-block mr-1" href="/issues?filter=On-going">
                <h3>{{$countOngoing ?? '?'}}</h3><small>On-Going</small>
            </a>
    </div>
</div>
