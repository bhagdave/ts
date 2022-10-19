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
                    <td >{{$reminder->title}}</td>
                    <td>{{$reminder->date}}</td>
                    <td>{{App\Reminder::RECURRENCE_SELECT[$reminder->recurrence] ?? ''}}</td>
                    <td>{{$reminder->reminders()->exists() ? 'Main' : ''}}</td>
                    <td>
                        <a href="/reminders/edit/{{$reminder->id}}" class="btn btn-outline-primary">Edit</a>
                        <form action="/reminders/delete/{{$reminder->id}}" 
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
