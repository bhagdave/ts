@extends('layouts.app')
@section('content')

<div class="container">
 	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Lets invite your tenants</h3>
			</div>
			<hr>
		</div>
	</div>
    <div class="row">



        <div class="col-sm-12 col-md-8 ">
            <form action="/invite/tenants" method="post" enctype="multipart/form-data">
                @csrf

                @foreach($properties as $property)
                    <div class="form-group row">
                        <p class="col-sm-3">
                            {{$property->propertyName}}
                        </p>
                        <input type="text" name="email[{{$property->id}}]" class="col-sm-4 form-control float-right" id="email"  placeholder="name@gmail.com" >
                        <input type="text" name="name[{{$property->id}}]" class="ml-2 col-sm-4 form-control float-right" id="name"  placeholder="firstname secondname">
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="card mt-2">
                <div class="card-body">
                    <h5 class="card-title">Help</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Inviting tenants</h6>
                    <p class="card-text">Please add an email address and name for each tenant you want to invite.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
