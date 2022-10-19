@extends('layouts.app')
<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector:'textarea.content',
        width: 900,
        height: 300
    });
</script>
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h3>{{ 'Edit hint for day ' . $hint->day  }}</h3>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <form method="POST" action="/hints/edit/post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{ $hint->id }}">
                    <input type="hidden" name="day" value="{{ $hint->day }}">

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text"
                               name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               id="name"
                               aria-describedby="reminder-title-help"
                               placeholder="Meaningful title for the day."
                               value="{{ $hint->title }}" required>

                        <small id="reminder-title-help" class="form-text text-muted">Enter a title for this hint.</small>

                        @error('hint')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <textarea class="content" name="content">
                            {{ $hint->content }}
                        </textarea>
                    </div>
                    <button type="submit" class="btn btn-primary float-right">
                        Update Hint
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
