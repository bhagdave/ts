@extends('layouts.app')
@section('content')
<div class="container">
    @if($landlords->count() > 0)
        <div class="row">
            <div class="col-sm-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h3>Your Landlords</h3>
                    <a href="landlord/create" class="btn btn-outline-primary"><span class="oi oi-plus"></span> Add Landlord</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="border-right border-warning border-top-0 border-bottom-0 bg-white rounded shadow-sm table-responsive">
                    <table class="table">
                        <tbody>
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Properties</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            @foreach ($landlords as $landlord)
                                <tr>
                                    <td>
                                        <a href="/landlord/{{ $landlord->id }}">
                                            @if (($landlord->user) && ($landlord->user->profileImage))
                                                <img src="{{$landlord->user->profileImage}}" class="rounded-circle mr-2" width="25" height="25">
                                            @endif

                                            <strong>{{ $landlord->name }}</strong>
                                        </a>
                                        @if (!$landlord->user_id)
                                            <span class="badge badge-pill badge-dark">Invitation Sent</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (count($landlord->property) > 0)
                                            <a href="/landlord/{{ $landlord->id }}/properties">View Properties [{{ count($landlord->property) }}]</a>
                                        @else
                                            <a href="/property/create">Add a Property</a>
                                        @endif
                                    </td>

                                </tr>
                                <tr>
                                    <td colspan="4">
                                        @include('landlords/details')	
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>


        </div>

        <div class="row">
        <div class="col-sm-12 d-flex justify-content-center">
            {{ $landlords->links() }}
        </div>
        </div>
    @else
    <div class="row my-md-5 justify-content-md-center">
        <div class="col-sm-12 col-md-6 align-self-center text-center">
            <div class="my-md-3 bg-white rounded">
                    <img src="https://images.unsplash.com/photo-1427751840561-9852520f8ce8?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=950&q=80" class="w-100">

                <div class="p-5">

                <h3>No landlords yet</h3>
                
                <h4 class="font-weight-light pt-2">Invite your first landlord to Tenancy Stream</h4>
                <a href="/landlord/create" class="btn btn-link btn-lg mt-2">Add First Landlord</a>
            </div>
            </div>
        </div>
    </div>

    @endif	
</div>
@endsection
