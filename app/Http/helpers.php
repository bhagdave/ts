<?php

use App\User;

//General helpers, for usage in views

function getUserByID($id){
	return User::where('id',$id);
}