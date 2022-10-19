@if ($user->userType=="Agent")
    <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h4>Quick Tasks</h4>
        <a href="/invite/user" class="btn btn-outline-primary btn">Add Teammate</a>
        <a href="/property" class="btn btn-outline-primary btn">Properties</a>
        <a href="/property/create" class="btn btn-outline-primary btn">Add property</a>
        <a href="/issues/create" class="btn btn-outline-primary btn">New Issue</a>
        <a href="/dmPage" class="btn  btn-outline-primary btn">New DM</a>
        <hr>
        <message-property :properties="{{$propertiesQuery}}"></message-property>
        <hr>
        <message-all></message-all>
    </div>
@endif
