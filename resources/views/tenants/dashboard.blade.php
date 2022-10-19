<div class="col-sm-12">
    <div class="container">
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h4>Welcome {{$user->firstName}}!</h4>
                @if (isset($tenantWaiting) && $tenantWaiting)
                    <div class="row pt-3 py-2 media-body pb-2 mb-0">
                        <div class="col float-left text-left">
                            We have sent an invite to your agent/landlord and are waiting for them to reply.  A reminder will be sent to them on a regular basis. While you are waiting for them
                            to accept you can contact them directly to remind them.  You can also add any issues you have with your property by clicking on the Report Issue button.
                        </div>
                    </div>            
                @else
                    @if (isset($tenantNeedsToInvite) && $tenantNeedsToInvite)
                        <div class="row pt-3 py-2 media-body pb-2 mb-0">
                            <div class="col float-left text-left"><strong>You need to invite your landlord/agent to tenancy stream so you can talk to them.</strong>
                                When they have accepted the invited you can ask them questions about the property and raise issues to notify them automatically.
                            </div>
                        </div>            
                        <div class="row pt-3 py-2 media-body pb-2 mb-0">
                            <div class="col float-left text-left">
                                <a href="agent/invite" class="btn btn-outline-primary invitation-button"><span class="oi oi-plus"></span> Add Agent/Landlord</a>
                            </div>
                        </div>            
                   @else
                       <div class="row pt-3 py-2 media-body pb-2 mb-0">
                            <div class="col-sm-6">
                                <p><strong>Your Home</strong><br>{{$user->tenant->property->inputAddress ?? ''}} {{$user->tenant->property->inputPostCode}}</p>
                            </div> 
                       </div>
                       <div class="row pt-3 py-2 media-body pb-2 mb-0">
                            <div class="col-sm-6">
                                @can('createIssue')
                                    <a href="/issues/create" class="btn btn-outline-primary invitation-button"><span class="oi oi-plus"></span> Report Issue</a>
                                @endcan
                                <a href="/stream/{{$user->tenant->property->stream_id}}" class="btn btn-outline-primary invitation-button"><span class="oi oi-chat"></span> Message Agent</a>
                                <a href="/issues" class="btn btn-outline-primary invitation-button"> <span class="oi oi-task "></span>My Issues</a>
                                <a href="/yourDocuments" class="btn btn-outline-primary invitation-button"> <span class="oi oi-document"></span>Your Documents</a>
                            </div>
                       </div>
                    @endif 
                @endif
        </div>
        <div class="row">
            <div class="col-sm-6">
                <user-activities ref="activities"></user-activities>
            </div>
            @if (!isset($tenantNeedsToInvite) || !$tenantNeedsToInvite || $tenantWaiting)
                <div class="col-sm-6">
                    @include('dashboard.advert')
                    @if($user->newsfeed)
                        <div class="my-3 p-3 bg-white rounded shadow-sm">
                            @include('dashboard.news')
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
