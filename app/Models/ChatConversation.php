<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    protected $guarded = ['id'];


	public function sender(){

		return $this->morphTo();
	}

	public function sender_type(){

		switch ($this->sender_type) {
			case 'App\Models\User':
				return 'User';
				break;

			case 'App\Models\Operator':
				return 'Operator';
				break;

			case 'App\Models\Admin':
				return 'Admin';
				break;
			default:
				return 'Unknown';
				break;
		}

		return 'Unknown';
	}
}
