@extends('layouts.app')
@section('content')
<div class="container">
	@if($properties->count() > 0)
        <div class="row">
            <div class="col-sm-12">
                <div class="pl-3 d-flex align-items-center justify-content-between">
                    <h3>Your Properties</h3>
                    @if (!($user->isAn('tenant')))
                        <a href="property/create" class="btn btn-outline-primary"><span class="oi oi-plus"></span> Add Property</a>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <table class="table-responsive pt-3 table-striped">
                    <tbody>
                        @foreach ($properties as $property)
                            <tr>
                                <th class="bg-success" scope="row">
                                    <h3 class="pl-2">{{ $property->propertyName }}</h3>
                                </th>
                                <td class="bg-success">{{ $property->inputAddress }}, {{ $property->inputPostCode }}</td>
                                <td class="bg-success">

                                    @if(count($property->issues) > 0)
                                        <a href="/propertyissues/{{ $property->id}}" class="text-danger"><span class="oi oi-cog text-danger"></span> {{count($property->issues)}}</a>
                                    @else
                                        <a href="/propertyissues/{{ $property->id}}" class="text-muted"><span class="oi oi-cog text-muted"></span> {{count($property->issues)}}</a>
                                    @endif


                                </td>
                                <td class="bg-success">
                                    <a href="/tenants/{{ $property->id }}" class="text-danger"><span class="oi oi-people text-danger"></span> {{count($property->tenants)}}</a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="row">
                                                <div class="col-sm-12 py-1">
                                                    @include('properties/card')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="row my-md-5 justify-content-md-center">
            <div class="col-sm-12 col-md-6 align-self-center text-center">
                <div class="my-md-3 bg-white rounded">
            <img src="https://images.unsplash.com/photo-1464082354059-27db6ce50048?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=950&q=100" class="w-100">
                    <div class="p-5">

                    <h3>No properties yet</h3>

                    @if ((!$user->isAn('tenant')))
                    <h4 class="font-weight-light pt-2">Add a property to get started</h4>
                    <a href="property/create" class="btn btn-link btn-lg mt-2">Add Property</a>
                    @endif
                </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12 d-flex justify-content-center">
            {{ $properties->links() }}
        </div>
    </div>
</div>
@endsection
