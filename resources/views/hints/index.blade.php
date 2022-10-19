@extends('layouts.app')
@section('content')
<main role="main" class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="d-flex align-items-center justify-content-between">
				<h3>Hints</h3>
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
                                <th>Day</th>
                                <th>Title</th>
                                <th></th>
                            </tr>
                        </thead>
                        @foreach($hints as $hint)
                            <tr>
                                <td >{{$hint->day}}</td>
                                <td>{{$hint->title}}</td>
                                <td>
                                    <a href="/hints/edit/{{$hint->id}}" class="btn btn-outline-primary">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
