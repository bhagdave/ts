<?php

namespace App\Http\Controllers;
use Spatie\Searchable\Search;
use Illuminate\Http\Request;
use App\User;
use App\Tenant;
use App\Properties;
use Illuminate\Support\Facades\Validator;
use App\Agent;
use App\Landlord;

class SearchController extends Controller
{
     public function data(Request $request){
         $validator = Validator::make($request->all(), [
             'query' => 'required',
         ]);
         if ($validator->fails()) {
             return redirect()->back()->with('message',  'No query requested');
         }
     	 $searchResults = (new Search())
           ->registerModel(Properties::class, 'propertyName','propertyType','propertyNotes','inputCity','inputAddress','inputPostCode')
           ->registerModel(Tenant::class, 'name','email','phone','notes')
           ->registerModel(Landlord::class, 'name','email','phone','notes')
          	->search($request->input('query'));
         return response()->json($searchResults);
     } 

    public function index(){
     	 $searchResults = (new Search())
           ->registerModel(Properties::class, 'propertyName','propertyType','propertyNotes','inputCity','inputAddress','inputPostCode')
           ->registerModel(Tenant::class, 'name','email','phone','notes')
           ->registerModel(Landlord::class, 'name','email','phone','notes')
          	->search('');
     } 


}
