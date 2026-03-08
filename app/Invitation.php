<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invitation extends Model
{
    protected $table = 'user_invitations'; 
    protected $fillable = ['code', 'email', 'user_id', 'status', 'valid_till', 'property_id', 'issue_id', 'agency_id'];

    public function getPendingInvites(){
        return $this->where('status', '=' , 'pending')
            ->whereRaw('valid_till > now()')
            ->where('user_invitations.created_at', '<=', Carbon::now()->subDays(3)->toDateTimeString() )
            ->get(); 
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

}
