<?php

namespace App\Http\Controllers\Properties;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Properties;
use App\Rent;

class RentController extends Controller
{
    public function index($propertyId){
        $property =  Properties::find($propertyId);
        if (is_null($property)) {
            abort(404);
        }
        $rentHistory = Rent::where('property_id', $propertyId)->orderBy('paid_date','desc')->paginate(20);
        return view('properties.rent', compact('rentHistory', 'property'));
    }

    public function paid(Request $request, $propertyId){
        $validate = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'paid_date' => 'date|before_or_equal:now',
            'amount' => 'required|numeric|min:1',
        ]);
        $rent = new Rent();
        $rent->fill($request->all());
        $rent->property_id = $propertyId;
        $rent->save();
        return redirect()->back()->with('message', 'Rent payment recorded')->withInput();
    }
}

