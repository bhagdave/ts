@extends('layouts.app')
@section('content')

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Documents for {{ $property->propertyName  }}</h3>
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
                    <document-table :page="'property'" :type="'property'" @documentUpload="documentUploaded($event)" :id="'{{ $property->id }}'"></document-table>
                    @if( $user->userType != 'Tenant')
                        @if ( $user->subscribed )
                            <document-upload :type="'property'" @documentUpload="documentUploaded($event)" :id="'{{ $property->id }}'"></document-upload>
                        @else
                            <div class="col-md-6 col-sm-12">
                                Your trial has come to and end and you have not subscribed so will not be able to add any more documents.<br>  Please <a href="{{url('payment')}}">click here</a> to subscribe.
                            </div>
                        @endif
                    @endif
                </div>
            </div>

		</div>


	</div>
   
</main>

@endsection
           
