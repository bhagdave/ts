<?php
/* Overrides Activity Logger function, to work around Eloquent User Model requirement*/

namespace App;

use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\String\Str;
use Illuminate\Support\Arr;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Config\Repository;
use Spatie\Activitylog\Exceptions\CouldNotLogActivity;
use App\User;
use Spatie\Activitylog\Contracts\Activity as ActivityContract;

class CustomActivityLogger extends ActivityLogger
{

    public function register() {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias( "\App\CustomActivityLogger","Spatie\Activitylog\ActivityLogger");
    }

    protected function normalizeCauser($modelOrId): Model
    {
       if ($modelOrId instanceof Model) {
            return $modelOrId;
        }

        $guard = $this->auth->guard($this->authDriver);
        $provider = method_exists($guard, 'getProvider') ? $guard->getProvider() : null;
        $model = method_exists($provider, 'retrieveById') ? User::current() : null;

        if ($model) {
            return $model;
        } 

        throw CouldNotLogActivity::couldNotDetermineUser($modelOrId);
    }
}
