<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Advert;

class AdvertController extends Controller
{
    public function recordClick($advertId){
        $advert = Advert::find($advertId);
        $advert->clicks += 1;
        $advert->save();
    }
}
