<?php

namespace App\Traits;
use App\Agent;
use App\User;
use App\Tenant;
use App\Contractor;
use Bouncer;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait MultitenantableProperty {

    protected static function bootMultitenantableProperty() {
        $user = User::current();
        if (isset($user)){
            if ($user->userType == "Agent") {
                $agent = Agent::where('user_id', Auth::user()->sub)->first();

                static::addGlobalScope('agent_id', function (Builder $builder) {
                    $user = Auth::user();
                    if ($user->userType == 'Agent' && isset($user->agent)){
                        $builder->where('agent_id', $user->agent->agency_id);
                    }
                });

            } elseif ($user->userType == "Landlord") {

                static::addGlobalScope('created_by_user_id', function (Builder $builder) {
                    $builder->where('created_by_user_id', Auth::user()->sub);
                });

            } elseif ($user->userType == "Tenant") {
                static::addGlobalScope('id', function (Builder $builder) {
                    if(is_null(Tenant::where('sub',Auth::user()->sub)->first())){
                        abort(403, 'Your access to this property has been denied. Please contact us for further assistance.');
                    }
                    $builder->where('properties.id', Tenant::where('sub',Auth::user()->sub)->first()->property_id);
                });
            } elseif (Bouncer::is(User::current())->a('undefined')) {
                    
            } elseif ($user->userType == "Admin") {
                //Bypass multi-tenancy for administrator
            } elseif ($user->userType == 'Contractor'){
                $contractor = Contractor::where('sub', '=', $user->sub);
                if ($contractor){
                    static::addGlobalScope('id', function (Builder $builder) {
                        $builder->join('issues', 'issues.property_id', '=', 'properties.id');
                        $builder->join('contractors', 'contractors.id', '=', 'issues.contractors_id');
                        $builder->join('users', function ($join){
                            $join->on('users.sub', '=', 'contractors.sub');
                            $join->on('users.sub', '=', DB::raw("'" . Auth::user()->sub . "'"));
                        });
                    });
                } else {
                    abort(403, 'ABORTED:Something went wrong with your account. Please contact us for further assistance.');
                }
            }

            else {
                abort(403, 'ABORTED:Your access to this property has been denied. Please contact us for further assistance.');
            }
        }

    }

}
