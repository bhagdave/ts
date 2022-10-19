<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    protected $table = 'rent_paid';
    protected $fillable = [
        'property_id',
        'tenant_id',
        'status',
        'paid_date',
        'amount'
    ];

    //Rent is paid by a tenant
    public function tenant()
    {
        return $this->belongsTo('App\Tenant', 'tenant_id');
    }

    public function property()
    {
        return $this->belongsTo('App\Properties','property_id');
    }
}
