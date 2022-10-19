@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 bg-white rounded shadow-sm">
            <div class="pt-3 mb-0" >
                <dm-page :contacts="{{$all}}"></dm-page>
            </div>
        </div>
    </div>
</div>
@endsection
