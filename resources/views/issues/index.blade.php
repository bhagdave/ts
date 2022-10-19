@extends('layouts.app')
@section('content')
@if($properties->count() > 0)
    @can('createIssue')
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="d-flex align-items-center">
                        <h3>Issues</h3>
                        <a href="/issues/create" class="ml-5 btn btn-outline-primary"><span class="oi oi-plus"></span> Add Issue</a>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    <div class="container">
        <div class="row flex-column-reverse flex-md-row">
            @if ($user->isAn('landlord') || $user->isAn('agent') || $user->isAn('admin'))
                <properties-table></properties-table>
            @endif
            <issues-table :usertype="'{{$user->userType}}'"  :filter="'{{$filter}}'"></issues-table>
            </div>
        </div>
    </div>
@else
    <div class="container">
        <div class="row my-md-5 justify-content-md-center">
            <div class="col-sm-12 col-md-6 align-self-center text-center">
                <div class="my-md-3 bg-white rounded">

                        <img src="https://images.unsplash.com/photo-1464082354059-27db6ce50048?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=950&q=100" class="w-100">

                    <div class="p-5">

                    <h3>No properties yet</h3>

                    <h4 class="font-weight-light pt-2">Add a property to get started</h4>
                    <a href="property/create" class="btn btn-link btn-lg mt-2">Add Property</a>
                </div>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
