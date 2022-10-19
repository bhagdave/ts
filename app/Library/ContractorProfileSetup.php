<?php

namespace App\Library;

use App\Library\ProfileSetup;
use Bouncer;
use App\Contractor;

class ContractorProfileSetup extends ProfileSetup
{
    public function setupProfileForContractor($user){
        $this->assignRolesToContractors($user);
        $this->createContractorsRecord($user);
    }
    private function assignRolesToContractors($user){
        if ($user->userType == "Contractor"){
            Bouncer::assign('contractor')->to($user);
            Bouncer::retract('tenant')->from($user);
            Bouncer::retract('agent')->from($user);
            Bouncer::retract('landlord')->from($user);
            Bouncer::retract('admin')->from($user);
            Bouncer::retract('undefined')->from($user);
            Bouncer::refreshFor($user);
        }
    }

    private function createContractorsRecord($user){
        $contractor = Contractor::where('sub', $user->sub)->first();
        if (is_null($contractor)){
            $contractor = new Contractor;
            $contractor->sub = $user->sub;
            $contractor->name = $user->firstName . ' ' . $user->lastName;
            $contractor->company = $user->companyName;
            $contractor->email = $user->email;
            $contractor->save();
        }        
    }
}
