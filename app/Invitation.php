<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invitation extends Model
{
    protected $table = 'user_invitations'; 
    protected $fillable = ['property_id','issue_id'];

    public function getPendingInvites(){
        return $this->where('status', '=' , 'pending')
            ->whereRaw('valid_till > now()')
            ->where('user_invitations.created_at', '<=', Carbon::now()->subDays(3)->toDateTimeString() )
            ->get(); 
    }

}
