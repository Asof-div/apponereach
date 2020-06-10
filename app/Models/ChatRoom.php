<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $guarded = ['id'];

    public function conversations()
    {
    	return $this->hasMany(ChatConversation::class)->oldest();
    }

    public function users(){

        return $this->belongsToMany(User::class, 'user_chat_room', 'chat_room_id', 'user_id');
    }

}
