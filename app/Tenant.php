<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MultitenantableTenants;
use App\Traits\usesUuid;
use App\Invitation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Tenant extends Model implements Searchable
{
    use UsesUuid;
    use MultitenantableTenants;

    public function getSearchResult(): SearchResult
     {
        $url = '/tenant/'.$this->id;
        if($this->user !=null ){
            $name =  $this->user->firstName." ".$this->user->lastName; 
        }
        else{
            $name =  $this->name;
        }
     
         return new \Spatie\Searchable\SearchResult(
            $this,
            $name,
            $url
         );
     }

    protected $fillable = ['name','email','property_id','phone','rentAmount','notes','rentDueInterval','moveInDate','profileImage','sub']; 
    protected $hidden = ['created_at', 'deleted_at',  'updated_at', 'rentAmount', 'rentDueInterval' ];

    //Tenants belong to properties
    public function property()
    {
        return $this->belongsTo('App\Properties');
    }

    public function user()
    {
    	return $this->hasOne(User::class ,'sub','sub');
    }

    public function invitation(){
        return $this->hasOne(Invitation::class, 'email', 'email');
    }


}
