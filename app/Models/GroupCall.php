<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;
use App\Traits\NonGlobalTenantScopeTrait;

class GroupCall extends Model
{
	use BelongsToTenant, NonGlobalTenantScopeTrait;

	protected $guarded = ['id'];

	protected $casts = [
        'members' => 'array',
    ];

    static function boot(){

        parent::boot();

    }

    public function tenant(){

    	return $this->belongsTo(Tenant::class);
    }

    public function call_flow(){

    	return $this->belongsTo(CallFlow::class);
    }

    public function assignedMembers(){

        return $this->hasMany(GroupMember::class, 'group_id');
    }

    public function getNumbersAttribute(){
    	$number = "";
    	foreach($this->members as $index => $member){
    		if(count($this->members) - 1 == $index){
	    		$number .= $member['member_number'];

    		}else{
	    		$number .= $member['member_number'] ." | ";
    		}
    	}
    	return $number;
    }

}
