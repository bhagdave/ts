@extends('layouts.app')
@section('content')
<main role="main" class="container">
    @if( !$user->subscribed )
        <p class="pb-3">
            Your trial has come to and end and you have not subscribed so will not be able to manage reminders.  
            Please <a href="{{url('payment')}}">click here</a> to subscribe.
        </p>
    @else
        <div class="row">
            <div class="col-sm-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h3>Manage all reminders</h3>
                </div>
                <small class="text-muted">Please note amending a main Reminder may impact all recurrences of that event</small>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive pt-3">
                    <table class="table">
                        <tbody>
                            <thead>
                                <tr>
                                    <th>Reminder</th>
                                    <th>Date</th>
                                    <th>Recurrence</th>
                                    <th>Main</th>
                                    <th></th>
                                </tr>
                            </thead>
                            @foreach($reminders as $reminder)
                                <tr>
                                    <td >{{$reminder['title']}}</td>
                                    <td>{{$reminder['date']}}</td>
                                    <td>{{App\Reminder::RECURRENCE_SELECT[$reminder['recurrence']] ?? ''}}</td>
                                    <td>{{$reminder['main'] ? 'Main' : ''}}</td>
                                    <td>
                                        <a href="/reminders/edit/{{$reminder['id']}}" class="btn btn-outline-primary">Edit</a>
                                        <form action="/reminders/delete/{{$reminder['id']}}" 
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this reminder?')"
                                            style="display: inline-block;"
                                        >
                                            {!! method_field('delete') !!}
                                            {!! csrf_field() !!}
                                            <input type="submit" class="btn  btn-outline-danger" value="Delete">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $reminders->links() ?? ''}}
            </div>
        </div>
    @endif
</main>
@endsection
