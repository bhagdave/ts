<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Hint;

class HintsController extends Controller
{
    public function index(){
        if (Auth::user()->userType == "Admin") {
            $hints = Hint::all();
            return view('hints.index', compact('hints'));
        }
        return redirect()->back()->with('message', 'You do not have permission to access that area!');
    }

    public function edit($hintId){
        if (Auth::user()->userType == "Admin") {
            $hint = Hint::find($hintId);
            return view('hints.edit', compact('hint'));
        }
        return redirect()->back()->with('message', 'You do not have permission to access that area!');
    }

    public function updateHint(Request $request){
        if (Auth::user()->userType == "Admin") {
            Hint::find($request->input('id'))->update($request->all());
            return redirect('hints')->with('message', "Hint for day " . $request->input('day') .  " updated." );
        }
        return redirect()->back()->with('message', 'You do not have permission to access that area!');
    }
}
