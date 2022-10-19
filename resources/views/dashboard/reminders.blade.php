<div class="my-3 p-3 bg-white rounded shadow-sm">
    <div class="d-flex align-items-center justify-content-between">
        <h4>Add a reminder</h4>
    </div>
    <div class="table-responsive pt-3">
        <quick-reminder :properties="{{$propertiesQuery}}"></quick-reminder>
    </div>
</div>
@if(isset($reminders)  && count($reminders) > 0)
    <div class="my-3 p-3 bg-white rounded shadow-sm">
        <div class="d-flex align-items-center justify-content-between">
            <h4>Upcoming reminders</h4>
        </div>
        <div class="table-responsive pt-3">
            <table class="table">
                <tbody>
                    <thead>
                        <tr>
                            <th>Reminder</th>
                            <th></th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    @foreach($reminders as $reminder)
                        <tr>
                            <td>{{$reminder->name}}</td>
                            <td>{{$reminder->property->propertyName}}</td>
                            <td>{{$reminder->start_date}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
