<?php
use App\Properties;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::channel('stream.{id}', function ($id) {
	$properties = \App\Properties::where('properties.id',$id)->get();

	if (is_null($properties)) {
		return false;
	}
	else{
		return true;
	}
});

Broadcast::channel('/stream.{id}', function ($id) {
	$properties = \App\Properties::where('properties.id',$id)->get();

	if (is_null($properties)) {
		return false;
	}
	else{
		return true;
	}
});

Broadcast::channel('users.{id}', function($id){
    return Auth::check();
});
