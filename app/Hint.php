<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hint extends Model
{
    protected $fillable = [
        'day', 'title', 'content' 
    ];

    public static function getHintForDay($day){
        return self::where('day', $day)->value('content'); 
    }
}
