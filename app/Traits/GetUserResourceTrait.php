<?php

namespace App\Traits;

use App\Http\Resources\UserResource;

trait GetUserResourceTrait
{

	public function getUser(){

		return new UserResource(request()->user());
	}


}