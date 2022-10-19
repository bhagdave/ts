@extends('layouts.app')
@section('content')
    @if (null !== session('wizard'))
        @if (session('wizard') == 'reminder')
            <reminder-wizard></reminder-wizard>
        @endif
    @endif
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h3>{{ isset($reminder) ? 'Edit ' . $reminder->name : 'Add a New Reminder' }}</h3>
                    @if(isset($model))
                        <small class="text_muted">Reminder on {{isset($model->propertyName) ? $model->propertyName : $model->name }}</small>
                    @endif
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <form method="POST" action="/reminders/{{ isset($reminder) ? 'edit' : 'create' }}/post" enctype="multipart/form-data">
                    @csrf

                    @if(isset($reminder))
                        <input type="hidden" name="id" value="{{ $reminder->id }}">
                    @endif
                    <input type="hidden" name="type" value="{{ isset($reminder) ? $reminder->type : $type }}">
                    <input type="hidden" name="type_id" value="{{ isset($reminder) ? $reminder->type_id : $typeId }}">

                    <div class="form-group">
                        <label for="title">Name</label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               aria-describedby="reminder-name-help"
                               placeholder="Remind me to..."
                               value="{{ old('name', isset($reminder) ? $reminder->name : '') }}" required>

                        <small id="reminder-name-help" class="form-text text-muted">Enter a name for this reminder.</small>

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" 
                               id="start_date" 
                               name="start_date" 
                               class="form-control datetime" 
                               aria-describedby="reminder-start-help"
                               value="{{ old('start_date', isset($reminder) ? $reminder->start_date : '') }}" required>
                        <small id="reminder-start-help" class="form-text text-muted">Enter a start date for this reminder.</small>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="recurrence">Repeating</label>
                        <select name="recurrence" id="recurrence">
                            @foreach(App\Reminder::RECURRENCE_SELECT as $key => $label)
                                <option value="{{$key}}" @if(isset($reminder) && $reminder->recurrence == $key) selected  @endif>{{$label}}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary float-right">
                        {{ isset($reminder) ? 'Update' : 'Create' }} Reminder
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
