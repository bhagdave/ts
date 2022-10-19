<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Agent;

class AgentsController extends Controller
{
    public function getAgentsOnAgency(){
        $user = Auth::user();
        if ($user->userType == 'Agent') {
            if (isset($user->agent)){
                return Agent::getAgentsOnAgency($user->agent->agency_id, $user->sub)->toJson();
            }
            return 'Err';
        }
        return 'Err';
    }
}
