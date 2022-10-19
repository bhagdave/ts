<?php

namespace App\Traits;
use App\Agent;
use App\User;
use App\Tenant;
use Bouncer;
use Auth;
use Illuminate\Database\Eloquent\Builder;

trait MultitenantableTenants
{
    protected static function bootMultitenantableTenants() {
        $user = Auth::user();
        if (isset($user)){
            if ($user->userType == "Agent") {
                // Only show tenants in properties managed or owned by the agent
                static::addGlobalScope('agent_id', function (Builder $builder) {
                    if (!is_null(Auth::user()->agent)) {
                        $builder->whereHas('property', function($q) {
                            $q->where('created_by_user_id', Auth::user()->sub)->orWhere('agent_id', Auth::user()->agent->agency_id);
                    });
                } 
                });

            } elseif ($user->userType == "Landlord") {

                // Only show tenants in properties owned by the landlord
                static::addGlobalScope('created_by_user_id', function (Builder $builder) {
                    $builder->whereHas('property', function($q) {
                        $q->where('created_by_user_id', Auth::user()->sub);
                    });
                });
            } elseif ($user->userType == "Contractor" || $user->userType == "undefined" || $user->userType == "Tenant" || $user->userType == "Admin") {
                //Do nothing
            } else {
                abort(403, 'Your access to this property has been denied. Please contact us for further assistance.');
            } 
        }
    }

}
