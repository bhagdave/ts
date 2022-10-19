<?php

namespace App\Library;

use App\Library\ProfileSetup;
use Bouncer;
use App\Landlord;

class LandlordProfileSetup extends ProfileSetup
{
    public function setupProfileForLandlord($user){
        $this->assignRolesToLandlords($user);
        $this->createLandlordRecord($user);
    }
    private function assignRolesToLandlords($user){
        if ($user->userType == "Landlord"){
            Bouncer::assign('landlord')->to($user);
            Bouncer::retract('agent')->from($user);
            Bouncer::retract('tenant')->from($user);
            Bouncer::retract('admin')->from($user);
            Bouncer::retract('contractor')->from($user);
            Bouncer::retract('undefined')->from($user);
            Bouncer::refreshFor($user);
        }
    }
    private function createLandlordRecord($user){
        $user->userType= "Landlord";

        $landlord = Landlord::where('user_id', $user->sub)->first();
        if (is_null($landlord)) {
            $landlord = new Landlord;
            $landlord->user_id=$user->sub;
            $landlord->save();
            $property = $this->createTestProperty($user);
            $this->createTestTenant($property);
            $this->createTestIssue($property, $user);
            $this->createReminder($property);
        }
    }
}
