<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rule;

class CommunityRules extends Controller
{
    public function index(){
        $rules = Rule::all();
        return view('rules', compact('rules'));
    }
}
