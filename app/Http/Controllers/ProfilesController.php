<?php

namespace App\Http\Controllers;

use App\Traits\UploadTrait;
use App\Library\ProfileSetup;
use Bouncer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilesController extends Controller
{
    use UploadTrait;

    public function welcome(Request $request)
    {
        $user = Auth::user();
        $property = \App\Properties::where('created_by_user_id', $user->sub)->first();

        return view('welcome', compact('user', 'property'));
    }

    public function userid(Request $request)
    {
        return response()->json(['id' => auth()->id()]);
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        $data = $request->only(['firstName', 'lastName', 'companyName', 'telephone']);

        if ($request->hasFile('profileImage')) {
            $data['profileImage'] = $this->uploadOne($request->file('profileImage'), 'profiles');
        }

        $user->fill($data);
        $user->registered = 1;
        $user->save();

        (new ProfileSetup())->setupProfileForUser($user);

        if ($user->userType === 'Tenant') {
            return redirect('/property/create');
        }

        return redirect('/');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->only(['firstName', 'lastName', 'companyName', 'telephone']);

        if ($request->hasFile('profileImage')) {
            $data['profileImage'] = $this->uploadOne($request->file('profileImage'), 'profiles');
        }

        $user->fill($data);
        $user->save();

        return redirect()->route('profile');
    }

    public function edit(Request $request)
    {
        $user = Auth::user();
        return view('profiles.edit', compact('user'));
    }

    private function assignRoleForUserType($user): void
    {
        $roleMap = [
            'Agent'      => 'agent',
            'Landlord'   => 'landlord',
            'Tenant'     => 'tenant',
            'Contractor' => 'contractor',
            'Admin'      => 'admin',
        ];
        $role = $roleMap[$user->userType] ?? null;
        if ($role) {
            Bouncer::assign($role)->to($user);
            Bouncer::refreshFor($user);
        }
    }
}
