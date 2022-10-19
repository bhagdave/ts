<?php

namespace App\Library;

use App\Library\ProfileSetup;
use Bouncer;
use App\Tenant;

class TenantProfileSetup extends ProfileSetup
{
    public function setupProfileForTenant($user){
        $this->assignRolesToTenants($user);
        $this->createTenantsRecord($user);
    }
    private function assignRolesToTenants($user){
        if ($user->userType == "Tenant"){
            Bouncer::assign('tenant')->to($user);
            Bouncer::retract('agent')->from($user);
            Bouncer::retract('landlord')->from($user);
            Bouncer::retract('admin')->from($user);
            Bouncer::retract('contractor')->from($user);
            Bouncer::retract('undefined')->from($user);
            Bouncer::refreshFor($user);
        }
    }

    private function createTenantsRecord($user){
        $tenant = Tenant::where('sub', $user->sub)->first();
        if (is_null($tenant)){
            $tenant = new Tenant;
            $tenant->sub = $user->sub;
            $tenant->name = $user->firstName . ' ' . $user->lastName;
            $tenant->email = $user->email;
            $tenant->property_id = null;
            $tenant->save();
        }        
    }
}
