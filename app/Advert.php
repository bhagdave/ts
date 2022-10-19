<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
    public static function getAdvertForType($userType){
        $adverts = Self::where('user_type', $userType)
            ->where('valid_from', '<', now())
            ->where('valid_to', '>', now())
            ->get();
        if (count($adverts) > 0){
            return $adverts->random();
        }
        return null;
    }
}
