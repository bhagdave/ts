<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\usesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contractor extends Model
{
    use UsesUuid;
    use SoftDeletes;

    protected $table = 'contractors'; 
    protected $fillable = [ 'phone', 'notes', 'name', 'company', 'email', 'profileimage'];

    public function categories(){
        return $this->belongsToMany('\App\Category')->withTimeStamps(); 
    }

    public function issues(){
        return $this->hasMany('\App\Issue', 'contractors_id');
    } 

    public function bids(){
        return $this->belongsToMany('\App\Issue')->withTimeStamps(); 
    }

    public function agencies(){
        return $this->belongsToMany('\App\Agency', 'agency_contractors');
    }
    public function user()
    {
    	return $this->hasOne(User::class ,'sub','sub');
    }
}
