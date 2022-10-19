<?php

namespace App\Traits;
use App\Agent;
use App\User;
use App\Tenant;
use Bouncer;
use Auth;
use Illuminate\Database\Eloquent\Builder;

trait MultitenantableLandlords {

	protected static function bootMultitenantableLandlords() {

		$user = Auth::user();

		if ($user->userType == "Agent") {

			// Only show tenants in properties managed or owned by the agent
			static::addGlobalScope('agent_id', function (Builder $builder) {
				$builder->where('agent_id', Auth::user()->agent->agency_id);
			});

		} elseif ($user->userType == "undefined") {

			// Allow full access so registration can be complete (checks if user is not a landlord)
			static::addGlobalScope('created_by_user_id', function (Builder $builder) {
				$builder->whereHas('property', function($q) {
            		
            	});
			});
		} elseif ($user->userType == "Landlord") {

			// Allow access to our profile
			static::addGlobalScope('user_id', function (Builder $builder) {
				$builder->where('user_id', Auth::user()->sub);
            		
			});
		} elseif ($user->userType == "Tenant") {
            static::addGlobalScope('id', function (Builder $builder) {
                if(is_null(\App\Tenant::where('sub',Auth::user()->sub)->first())){
                    abort(403, 'Your access to this property has been denied. Please contact us for further assistance.');
                }
                $builder->where('id', \App\Tenant::where('sub',Auth::user()->sub)->first()->property_id);
            });

        } elseif ($user->userType == "Admin") {

		}
		else {
			abort(403, 'Your access to this property has been denied. Please contact us for further assistance.');
		}

	}

}
