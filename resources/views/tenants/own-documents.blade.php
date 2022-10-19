@extends('layouts.app')
@section('content')

<main role="main" class="container">
	<div class="row pt-3 justify-content-md-center">
		<div class="col-sm-12 col-md-12 align-self-center">
            <div class="">
                <div class="row d-flex">
                    <document-table :page="'your'" :type="'tenant'" @documentUpload="documentUploaded($event)" :id="'{{ $user->tenant->id }}'"></document-table>
                </div>
            </div>
		</div>
	</div>
</main>

@endsection
           
