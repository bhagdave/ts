@extends('layouts.app')
@section('content')
<script type='text/javascript'>
    function copyToClipboard(text){
        console.log(text);
        navigator.clipboard.writeText(text).then(function(){
            alert("Link copied to clipboard!");
        }, function(){
            alert("Unable to copy link to clipboard!");
        });
        return true;
    }
</script>
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Tenants at <a href="/property/{{$property->id}}">{{$property->propertyName }}</a></h3>
				 @if ($user->userType=="Agent" || $user->userType=="Admin")
				<a href="/tenant/create" class="btn btn-outline-primary"><span class="oi oi-plus"></span> Add Tenants</a>
				 @endif
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="table-responsive pt-3">
				<table class="table">
					<tbody>
						<thead>
							<tr>
								<th scope="col">Name</th>
								<th scope="col">Rent</th>
								<th scope="col"></th>
							</tr>
						</thead>
						@foreach ($tenants as $tenant)
						<tr>
							<td>
                                @if ($tenant->user)
									@if ($tenant->user->profileImage)
                                        <img src="{{$tenant->user->profileImage}}" class="rounded-circle mr-3" width="25" height="25">
									@else
                                        <img src="/uploads/images/_default.jpg" class="rounded-circle mr-3" width="25" height="25">
                                    @endif
								@endif

                                @if ($tenant->user !=null) 
                                    {{$tenant->user->firstName}} {{$tenant->user->lastName}} 
                                @else 
                                    <img src="/uploads/images/_default.jpg" class="rounded-circle mr-3" width="25" height="25"> {{$tenant->name}} 
                                @endif
                                

                                @if (!$tenant->sub)
                                    @if(isset($tenant->invitation))
                                        <span onclick="copyToClipboard('https://www.tenancystream.app/invite/{{$tenant->invitation->code}}')"  class="invite-link badge badge-pill badge-dark ml-2">Share Invitation link</span>
                                    @endif
                                @endif
							</td>
							<td>@if ($tenant->rentAmount) £{{$tenant->rentAmount}} paid {{$tenant->rentDueInterval}} @else Not Specified @endif</td>
							<td class="text-right">
								<a class="btn btn-outline-primary btn-sm" data-toggle="collapse" href="#collapseExample-{{$loop->iteration}}" role="button" aria-expanded="false" aria-controls="collapseExample-{{$loop->iteration}}">
									<span class="oi oi-chevron-bottom"></span>
							</a>

								
							</td>
						</tr>
                        <tr class="collapse" id="collapseExample-{{$loop->iteration}}">
                            <td colspan="4">
                                @include('tenants/details')
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			
			<div class="d-flex align-items-center justify-content-between">
			</div>
			
		</div>
	</div>
	
</div>
@endsection
