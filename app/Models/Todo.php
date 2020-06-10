<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use App\Traits\NonGlobalTenantScopeTrait;

class Todo extends Model
{
    use BelongsToTenant, NonGlobalTenantScopeTrait;
	
    protected $guarded = ['id'];

    public static function boot() {
        
        parent::boot();

        static::creating(function($todo) {
            
            
        });


    }

    public function tenant(){

    	return $this->belongsTo(Tenant::class);
    }

    public function assignee(){

    	return $this->belongsTo(User::class, 'assignee_id');
    }

    public function assigner(){

    	return $this->belongsTo(User::class, 'assigner_id');
    }


    public function completed(){

        if(strtolower($this->status) == 'completed' ){
            return true;
        }

        return false;
    }


    public function comments()
    {
        return $this->hasMany(Comment::class, 'todo_id')->latest();
    }

    public function setRepeatIntervalOptsAttribute($opts)
    {
        $this->attributes['repeat_interval_opts'] = !is_null($opts) ? json_encode($opts) : null;
    }

    public function getRepeatIntervalOptsAttribute($opts)
    {
        if (!is_null($opts)) {
            return json_decode($opts, true);
        } else {
            return null;
        }
    }

    public function repeat(){
        if($this->repeat_task){

            return $this->repeat_interval .', Until '.  (new \DateTime($this->repeat_end_date))->format('Y-m-d');
        }
        return "ONCE";
    }

}
