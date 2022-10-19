<?php

namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Auth;
use App\Tenant;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function getAgents(){
        $user = Auth::user();
        if ($user->userType == 'Tenant'){
            $agents = $this->getAgentsForMenu($user);
            return $agents->toJson();
        }
        return 'Err';
    }

    private function getAgentsForMenu($user){
        $agents = Tenant::where('tenants.sub','=', $user->sub)
            ->select('agents.id', 'agents.name')
            ->whereNotNull('agents.user_id')
            ->whereNull('agents.deleted_at')
            ->join('properties', 'tenants.property_id', '=', 'properties.id')
            ->join('agency', 'properties.agent_id', '=', 'agency.id')
            ->join('agents', 'agency.id', '=', 'agents.agency_id');
        $agents->addSelect(DB::raw("'Agent' as type"));
        return $agents->get();
    }
}
