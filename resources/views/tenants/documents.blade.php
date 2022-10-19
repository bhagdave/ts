@extends('layouts.app')
@section('content')

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Documents for @if ($tenant->user !=null) {{$tenant->user->firstName}} {{$tenant->user->lastName}} @else {{$tenant->name}} @endif</h3>
			</div>
			<hr>
		</div>
	</div>
</div>
<main role="main" class="container">
	<div class="row pt-3 justify-content-md-center">
		<div class="col-sm-12 col-md-12 align-self-center">
            <div class="">
                <div class="row d-flex">
                    <document-table :page="tenant" :type="'tenant'" @documentUpload="documentUploaded($event)" :id="'{{ $tenant->id }}'"></document-table>
                    <document-upload :type="'tenant'" @documentUpload="documentUploaded($event)" :id="'{{ $tenant->id }}'"></document-upload>
                </div>
            </div>

		</div>


	</div>
   
</main>

@endsection
           
