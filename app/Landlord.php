<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\usesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\MultitenantableLandlords;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Landlord extends Model implements Searchable
{

    use MultitenantableLandlords;
    use UsesUuid;

    public function getSearchResult(): SearchResult
     {
        $url = '/landlord/'.$this->id;
     
         return new \Spatie\Searchable\SearchResult(
            $this,
            $this->name,
            $url
         );
     }

	protected $fillable = ['name','agent_id','email']; 
    protected $appends = ['sub'];

    //Landlords can belong to Agents
    public function agent()
    {
        return $this->belongsTo('App\Agency', 'agent_id', 'id');
    }

    //Landlords own properties
    public function property()
    {
        return $this->hasMany('App\Properties','created_by_user_id','user_id');
    }

    public function user()
    { 
        return $this->belongsTo(User::class,'user_id','sub');
    }

    public function getSubAttribute(){
        return $this->user_id;
    }
}
