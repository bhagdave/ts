<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;
use App\Traits\usesUuid;

class Agency extends Model
{
    use UsesUuid;
    use Billable;

    protected $table = 'agency';
    protected $fillable = ['company_name', 'phone', 'profile_image', 'stream_id', 'trial_ends_at'];
    protected $dates = ['trial_ends_at'];


    public function agents(){
        return $this->hasMany('\App\Agent');
    }

    public function contractors(){
        return $this->belongsToMany('\App\Contractor', 'agency_contractors');
    }

    public function properties(){
        return $this->hasMany('\App\Properties', 'agent_id');
    }

    public function landlords(){
        return $this->hasMany('\App\Landlord', 'agent_id');
    }

    public function tenants(){
        return $this->hasManyThrough( '\App\Tenant','\App\Properties','agent_id', 'property_id', 'id');
    }

    public static function createAgencyFromCompanyName($companyName, $streamId){
        $agency = new Agency();
        $agency->company_name = $companyName;
        $agency->stream_id = $streamId;
        $agency->trial_ends_at = now()->addDays(30);
        $agency->save();
        return $agency;
    }

    public function mainAgent(){
        return $this->agents->where('main', '=', 1)->first();
    }
    public function getSubscribedAttribute(){
        $subscribed =  $this->subscribed('standard');
        $onTrial = $this->onTrial();
        return $this->founder || $subscribed || $onTrial;
    }
    public function reminders(){
        return $this->hasManyThrough('App\Reminder', 'App\Properties', 'agent_id', 'type_id');
    }
}
