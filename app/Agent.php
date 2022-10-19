<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\usesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use UsesUuid;

    use SoftDeletes;

    protected $fillable = ['name','user_id', 'agency_id', 'country', 'property_count']; 
    protected $appends = ['sub'];
    protected $hidden = [ 'updated_at', 'created_at', 'deleted_at', 'agency_id'];

    public static function getAgentsOnAgency($agencyId, $userSub){
        return Agent::where('agency_id', '=', $agencyId)
            ->where('user_id', '!=', $userSub)
            ->get();
    }


    public function agency(){
        return $this->belongsTo('\App\Agency');
    }

    public function user(){
        return $this->hasOne('\App\User', 'sub', 'user_id');
    }
    public function getSubAttribute(){
        return $this->user_id;
    }
}
