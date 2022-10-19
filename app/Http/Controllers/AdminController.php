<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\User;
use Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::user()->userType == "Admin") {
            return view('admin.index');
        }
        return redirect()->back()->with('message', 'You do not have permission to access that area!');
    }

    public function users()
    {
        if (Auth::user()->userType == "Admin") {
            $users = User::where('id', '!=', auth()->id())->paginate(15);
            $users = $this->getTemporaryLink($users);
            return view('admin.users', compact('users'));
        }
        return redirect()->back()->with('message', 'You do not have permission to access that area!');
    }

    private function getTemporaryLink($users){
        foreach ($users as $user) {
            $user->magicLink= URL::temporarySignedRoute(
                'autologin',
                now()->addDay(),
                [
                    'user_id'       => $user->id,
                    'url_redirect'  => '/',
                ]
            );
        }
        return $users;
    }

    public function deleteuser($id)
    {
        $user = Auth::user();
        if ($user->userType == "Admin") {
            User::where('id', '=', $id)->delete();
            return redirect('/admin/')->with('message', 'Deleted successfully');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
    public function usersearch(Request $request){
        $user = Auth::user();
        if ($user->userType == "Admin") {
            if ($request->input('search')){
                $search = $request->input('search');
                $userType = $request->input('usertype');
                $users = User::where('id', '!=', auth()->id())
                    ->where('email', 'like', '%' . $search . '%')
                    ->when($userType, function($query) use($userType){
                        return $query->where('userType', $userType);
                    })->get();
                $users = $this->getTemporaryLink($users);
                return view('admin.users', compact('users', 'search', 'userType'));
            }
        }
        abort(403, 'Unauthorized action.');
    }
    public function agents()
    {
        if (Auth::user()->userType == "Admin") {
            $userType = 'Agent';
            $users = $this->getUsersForType($userType);
            return view('admin.users', compact('users', 'userType'));
        }
        return redirect()->back()->with('message', 'You do not have permission to access that area!');
    }
    private function getUsersForType($userType){
        $users = User::where('id', '!=', auth()->id())
            ->where('userType', '=', $userType)
            ->paginate(15);
        $users = $this->getTemporaryLink($users);
        return $users;
    }
    public function landlords()
    {
        if (Auth::user()->userType == "Admin") {
            $userType = 'Landlord';
            $users = $this->getUsersForType($userType);
            return view('admin.users', compact('users', 'userType'));
        }
        return redirect()->back()->with('message', 'You do not have permission to access that area!');
    }
    public function tenants()
    {
        if (Auth::user()->userType == "Admin") {
            $userType = 'Tenant';
            $users = $this->getUsersForType($userType);
            return view('admin.users', compact('users', 'userType'));
        }
        return redirect()->back()->with('message', 'You do not have permission to access that area!');
    }
}
