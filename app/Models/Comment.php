<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\NonGlobalTenantScopeTrait;

class Comment extends Model
{
	use NonGlobalTenantScopeTrait;

    protected $guarded = ['id'];



    public function resources(){

        return $this->belongsToMany(Resource::class, 'ticket_resources', 'comment_id', 'resource_id')->withPivot(['allow_tenant'])->withTimestamps()->orderBy('created_at', 'desc');
    }

	public function commentable(){

		return $this->morphTo();
	}
	

}
