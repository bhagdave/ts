<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories'; 

    public function contractors(){
        return $this->belongsToMany('\App\Contractor')->withTimeStamps(); 
    }

    public function issues(){
        return $this->hasMany('\App\Issue');
    } 
}
