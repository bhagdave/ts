@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
	    <div class="col-sm-12">
		    <div class="d-flex align-items-center justify-content-between">
			    <h3>Edit Issue</h3>
			</div>
			<hr>
		</div>
	</div>
	<div class="row">
	    <div class="col-sm-12">
		    <form method="POST" action="/issue/{{ $issue->id }}/updateIssue" enctype="multipart/form-data">
			    @csrf
				@if ($user->isAn('agent') || $user->isAn('landlord'))
				<div class="form-group">
				    <label for="name">Change Title</label>
				    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="My Issue Title" value="{{ $issue->extra_attributes->title ?? "Issue"}}">
				    @error('title')
	    				<div class="invalid-feedback">{{ $message }}</div>
					@enderror
			    </div>
			    @endif

			    <div class="form-group">
				    <label for="name">Description</label>
                    <textarea class="form-control  @error('mainDescription') is-invalid @enderror" name="mainDescription" id="mainDescription" value="{{ $issue->extra_attributes->mainDescription}}" required>{{ $issue->extra_attributes->mainDescription}}</textarea>
				    <small id="emailHelp" class="form-text text-muted">
                        Type a detailed description of the issue that would enable someone to find and reproduce the issue
                    </small>

				    @error('mainDescription')
	    				<div class="invalid-feedback">{{ $message }}</div>
					@enderror
			    </div>
                
                @include('issues/form')
				
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>
	
</div>
@endsection
