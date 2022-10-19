<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MultitenantableProperty;
use App\Traits\usesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Properties extends Model implements Searchable
{

	use MultitenantableProperty;
	use UsesUuid;
	use SoftDeletes;

    public function getSearchResult(): SearchResult
     {
        $url = '/property/'.$this->id;
     
         return new \Spatie\Searchable\SearchResult(
            $this,
            $this->propertyName,
            $url
         );
     }
	
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'properties'; //Override 'Propertys' Laravel default
    protected $fillable = ['propertyName','propertyType','propertyNotes','totalRent','tenantsTotal','inputAddress','inputAddress2','inputCity','inputRegion','inputPostCode','user_id','created_by_user_id','profileImage','propertyLat','propertyLng','agent_id'];
    protected $hidden = ['created_at', 'deleted_at', 'private_stream_id', 'updated_at', 'tenantsTotal', 'totalRent', 'created_by_user_id'];

    //Properties have many tenants - foreign key = property_id
    public function tenants()
    {
        return $this->hasMany('App\Tenant','property_id','id');
    }

    //Properties have many issues
    public function issues()
    {
        return $this->hasMany('App\Issue','property_id','id');
    }

    //Properties have a landlord - foreign key = created_by_user_id, local key = sub [from Auth0]
    public function landlord()
    {
        return $this->belongsTo('App\User','created_by_user_id','sub');
    }

    //Properties can have a agent 
    public function agent()
    {
        return $this->hasOneThrough(
            'App\Agent',
            'App\Agency',
            'id',
            'agency_id',
            'agent_id',
            'id'
        );
    }

    public function agents(){
        return $this->hasManyThrough(
            'App\Agent',
            'App\Agency',
            'id',
            'agency_id',
            'agent_id',
            'id'
        );
    }

    public function propertyIdtoStreamId($id){
        $streamModel =  new Stream();
        $stream = $streamModel->withExtraAttributes('property_id',$id)->first();
        if (is_null($stream)){
            return 0;
        } else {
            return $stream->id;
        }
    }

    public function propertyIdtoName($id){
        $property = \App\Properties::find($id);
        if(empty($property->propertyName)){
            return 'Unknown';
        } else{
           return $property->propertyName;
        }

    }


    public function stream(){
        return $this->belongsTo('\App\Stream');
    }

    public function agency(){
        return $this->belongsTo('\App\Agency', 'agent_id');
    }

    public function rent()
    {
        return $this->hasMany('App\Rent','property_id','id');
    }
}
