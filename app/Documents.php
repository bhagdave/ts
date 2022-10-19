<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\usesUuid;


class Documents extends Model
{

    use UsesUuid;

    protected $table = 'document_storage';
    protected $fillable = [
        'type',
        'linked_to',
        'path',
        'file_type'
    ];

}
