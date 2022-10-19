@extends('layouts.app')
@section('content')
    <main role="main" class="container">
        <div class="row pt-2">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header text-center">
                        Community Rules
                    </div>
                    <div class="card-body">
                        <p class="card-text">We want our chat and discussion boards to be both helpful and entertaining and our rules about how to use them ensure respectul and courteous use.
                        Users can receive support, learn more about Stream members and their interests and meet new friends on our boards. Our rules around the use of our boards help ensure 
                        a positive and enjoyable experience for all our users</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($rules as $rule)
                            <li class="list-group-item">{!! $rule->rule !!}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
</main>
@endsection
