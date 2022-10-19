<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Carbon\Carbon;
use Junaidnasir\Larainvite\InviteTrait;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable
{
    use Notifiable;
    use HasRolesAndAbilities;
    use InviteTrait;

    protected $fillable = [
        'name', 'email', 'password','userType','firstName','lastName','profileImage','sub','companyName', 'registered', 'email_notifications', 'telephone'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function property()
    {
        return $this->hasMany('App\Properties','sub','created_by_user_id');
    }

    public function sentMessages(){
        return $this->hasMany('App\Message', 'sub', 'creator_sub');
    }

    public function receivedMessages(){
        return $this->hasMany('App\Message', 'sub', 'recipient_sub');
    }

    public function agent()
    {
        return $this->belongsTo('App\Agent','sub','user_id');
    }

    public function landlord() 
    { 
        return $this->belongsTo(Landlord::class,'sub','user_id');
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class ,'sub','sub');
    }

    public function recordActive(){
        $this->last_activity = $this->freshTimestamp();
        $this->nologin_email = false;
        return $this->save();
    }

    public static function getUserBySub($sub){
        return User::where('sub', '=', $sub)->get()->first(); 
    }

    public function getUsersToNotify(){
        return $this->where('last_activity', '<=' , Carbon::now()->subDays(3)->toDateTimeString() )
            ->where('nologin_email', '=', false)->get(); 
    }

    public static function current() {
        if(Auth::check()) {
            return User::find(Auth::user()->id);
        } else {
            return null;
        }
    }
    public function receivesBroadcastNotificationsOn() 
    { 
        return 'users.'.$this->sub; 
    } 

    public function getTypeId(){
        if ($this->userType == 'Tenant'){
            return $this->tenant->id;
        }
        if ($this->userType == 'Agent'){
            return $this->agent->id;
        }
        if ($this->userType == 'Landlord'){
            return $this->landlord->id;
        }
    }

    public function streams(){
        return $this->belongsToMany('\App\Stream')
            ->withPivot('last_visited');
    }

    public function contractor(){
        return $this->hasOne(Contractor::class ,'sub','sub');
    }
    /*
     * This is an attribute to identify if the user can use some of the facilities
     * It looks if they are a founder member, if they are still within trial or if 
     * they have paid for a subscription
     */
    public function getSubscribedAttribute(){
        if ($this->userType == 'Agent'){
            $agency = $this->agent->agency;
            return ($agency->founder || $agency->onTrial() || $agency->subscribed('standard'));
        }
        return true;
    }
}
