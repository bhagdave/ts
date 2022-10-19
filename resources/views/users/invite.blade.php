@extends('layouts.app')
@section('content')

<div class="container">
 	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Lets invite your colleague!</h3>
			</div>
			<hr>
		</div>
	</div>
    <div class="row">



        <div class="col-sm-12 col-md-8 ">
                @csrf
                <agent-invite></agent-invite>
        </div>
            <div class="col-sm-12 col-md-4">
         	    <div class="card mt-2">
                    <div class="card-body">
                        <h5 class="card-title">Help</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Adding Users</h6>
                        <p class="card-text">Enter basic details about your colleague here. We'll then send them an invitation to access Tenancy Stream automatically.</p>
                    </div>
                </div>
            </div>
    </div>

</div>
@endsection
