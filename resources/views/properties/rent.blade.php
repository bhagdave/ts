@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="bg-success d-flex align-items-center justify-content-between">
				<h3>Rental data for {{ $property->propertyName }}</h3>
                {{ $property->inputAddress }}, {{ $property->inputPostCode }}
			</div>
			<hr>
		</div>
	</div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h4>Add a payment</h4>
            <form method="POST" action="/property/rent/{{ $property->id }}/paid" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-sm-6 col-md-6">
                        <label for="tenant_id">Tenant</label>
                        <select id="tenant_id" required name="tenant_id" class="form-control @error('tenant_id') is-invalid @enderror">
                            @foreach($property->tenants as $tenant)
                                <option value="{{$tenant->id}}">{{$tenant->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-md-6">
                        <label for="paid_date">Date Paid</label>
                        <input type="date" name="paid_date" class="form-control @error('paid_date') is-invalid @enderror">
                        @error('paid_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6 col-md-6">
                        <label for="amount">Amount</label>
                        <input 
                            type="number" 
                            min="0.00"
                            max="100000"
                            step="1"
                            name="amount" 
                            class="form-control @error('amount') is-invalid @enderror" 
                            id="amount" 
                            placeholder="250.00" 
                            required
                        >
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-sm-6 col-md-6">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="ONTIME">On Time</option>
                            <option value="LATE">Late</option>
                            <option value="EARLY">Early</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
				<button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h4 class="text-center">Payment History</h4>
            <table class="table pt-3 table-striped">
                <tbody>
                    <tr>
                        <th>Tenant</th>
                        <th class="">Paid Date</th>
                        <th class="">Status</th>
                        <th class="">Amount Paid</th>
                    </tr>
                    @foreach ($rentHistory as $rent)
                        <tr>
                            <td>{{$rent->tenant->name}}</td>
                            <td>{{$rent->paid_date}}</td>
                            <td>{{$rent->status}}</td>
                            <td>£{{$rent->amount}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 d-flex justify-content-center">
            {{ $rentHistory->links() }}
        </div>
    </div>
</div>
@endsection
