<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MultitenantableProperty;
use App\Traits\usesUuid;
use App\Contractor;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class Issue extends Model
{
    
	use UsesUuid;
	use SoftDeletes;

	//Required for Spatie Schemaless Attributes to work on this model

	public $casts = [
        'extra_attributes' => 'array',
    ];

    public function getExtraAttributesAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'extra_attributes');
    }

    public function scopeWithExtraAttributes(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('extra_attributes');
    }


	protected $table = 'issues';
	protected $fillable = ['invite', 'location','description','property_id','attributes', 'categories_id', 'contractors_id'];

	//Issues belong to properties
    public function property()
    {
        return $this->belongsTo('App\Properties','property_id');
    }

    public function contractor(){
        return $this->belongsTo('\App\Contractor', 'contractors_id');
    }

    public function category(){
        return $this->belongsTo('\App\Category', 'categories_id');
    }

    public function bidders(){
        return $this->belongsToMany('\App\Contractor')->withTimeStamps(); 
    }
    public static function getIssuesForUser($user, $propertyId = null){
        $issuesQuery = null;
        if ($user->userType == 'Agent'){
            $issuesQuery = Self::getIssuesForAgent($user, $propertyId);
        }
        if ($user->userType =='Landlord'){
            $issuesQuery = Self::getIssuesForLandlord($user, $propertyId);
        }
        if ($user->userType =='Tenant'){
            $issuesQuery = Self::getIssuesForTenant($user, $propertyId);
        }
        if ($user->userType =='Admin'){
            $issuesQuery = Self::getIssuesForAdmin($user, $propertyId);
        }
        if ($user->userType == 'Contractor'){
            $issuesQuery = Self::getIssuesForContractor($user, $propertyId);
        }
        return $issuesQuery;
    }

    private static function getIssuesForAgent($user, $propertyId){
        if (isset($user->agent)){
            $issuesQuery = Self::whereHas('property', function($q) use ($user) {
                $q->where('created_by_user_id', $user->sub)->orWhere('agent_id', $user->agent->agency_id);
            })->join('properties', 'properties.id', '=', 'issues.property_id')
                ->select(DB::raw('issues.*, concat(LEFT(issues.description, 50), "....") as short_description, properties.propertyName as property'));
            if (isset($propertyId)){
                $issuesQuery->where('property_id', $propertyId);
            }
            $issuesQuery->orderBy('created_at','desc')->get();
            return $issuesQuery;
        }
        return null;
    }


    private static function getIssuesForLandlord($user, $propertyId){
        $issuesQuery = Self::whereHas('property', function($q) use ($user) {
            $q->where('created_by_user_id', $user->sub);
        })->join('properties', 'properties.id', '=', 'issues.property_id')
            ->whereJsonContains('extra_attributes->confidential', 'false')
            ->orWhere(DB::raw("JSON_EXTRACT(extra_attributes, '$.confidential') IS NULL"))
            ->select(DB::raw('issues.*, concat(LEFT(description, 50), "....") as short_description, properties.propertyName as property'));
        if (isset($propertyId)){
            $issuesQuery->where('property_id', $propertyId);
        }
        $issuesQuery->orderBy('created_at','desc')->get();
        return $issuesQuery;
    }

    private static function getIssuesForTenant($user, $propertyId){
        $issuesQuery = Self::where('property_id',  $user->tenant->property_id)
            ->join('properties', 'properties.id', '=', 'issues.property_id')
            ->where(function ($q){
                $q->whereJsonContains('extra_attributes->confidential', 'false')
                ->orWhere(DB::raw("JSON_EXTRACT(extra_attributes, '$.confidential') "));
            })->where(function ($q) use($user){
                $q->where('issues.private','=', 0)
                ->orWhere('creator_id', $user->sub);
            })
            ->select(DB::raw('issues.*, concat(LEFT(description, 50), "....") as short_description, properties.propertyName as property'));
        if (isset($propertyId)){
            $issuesQuery->where('property_id', $propertyId);
        }
        $issuesQuery->orderBy('created_at','desc')->get();
        return $issuesQuery;
    }
    
    private static function getIssuesForAdmin($user, $propertyId){
        $issuesQuery = Self::orderBy('updated_at','desc')
            ->join('properties', 'properties.id', '=', 'issues.property_id')
            ->select(DB::raw('issues.*, concat(LEFT(description, 50), "....") as short_description, properties.propertyName as property'));
        if (isset($propertyId)){
            $issuesQuery->where('property_id', $propertyId);
        }
        return $issuesQuery->get();
    }
    
    private static function getIssuesForContractor($user, $propertyId){
        $contractor = Contractor::where('sub', '=', $user->sub)->first();
        $issuesQuery = Self::orderBy('updated_at','desc')
            ->join('properties', 'properties.id', '=', 'issues.property_id')
            ->select(DB::raw('issues.*, concat(LEFT(description, 50), "....") as short_description, properties.propertyName as property'))
            ->where('contractors_id', '=', $contractor->id);
        if (isset($propertyId)){
            $issuesQuery->where('property_id', $propertyId);
        }
        return $issuesQuery->get();
    }
    
    public static function getOpenIssuesForContractor($contractor){
        $categories = $contractor->categories()->pluck('id');
        $bids = $contractor->bids()->pluck('id');
        return DB::table('issues')->select('issues.id', 'description', 'categories.name', 'inputAddress', 'inputPostcode')
            ->join('properties', 'property_id', '=', 'properties.id')
            ->join('categories', 'categories_id', '=', 'categories.id')
            ->whereIn('categories_id', $categories)
            ->whereNotIn('issues.id', $bids)
            ->whereNull('issues.deleted_at')
            ->whereNull('issues.contractors_id')
            ->whereNull('issues.invite')
            ->where('attributes', 'Open')
            ->get();
            
    }
    public function scopeOpen($query){
        return $query->where('attributes', '!=', 'Closed');
    }
}
