<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\usesUuid;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class Stream extends Model
{

    use UsesUuid;

	public $casts = [
        'extra_attributes' => 'array',
    ];

    public $fillable = ['streamName', 'private'];

    public function getExtraAttributesAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'extra_attributes');
    }

    public function scopeWithExtraAttributes(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('extra_attributes');
    }

	protected $table = 'stream'; //Override 'Streams' Laravel default


    public function property(){
        return $this->hasOne('\App\Properties');
    }

    public function users(){
        return $this->belongsToMany('\App\User')
            ->withPivot('last_visited');
    }
}
