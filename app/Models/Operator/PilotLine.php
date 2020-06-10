<?php

namespace App\Models\Operator;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;
use App\Services\PilotDefaultDestination;

use App\Traits\NonGlobalTenantScopeTrait;

class PilotLine extends Model
{
    use NonGlobalTenantScopeTrait;
    
    protected $guarded = ['id'];

    public static function boot() {
        
        parent::boot();

        static::creating(function($pilot) {
            $inbox = VoicemailInbox::create([
                'number' => $pilot->number,
                'context' => '',
                'type' => 'pilot_line',
                'email' => '',
                ]);

            $pilot->voicemail_inbox_id = $inbox->id;

        });


        static::deleting(function ($pilot) {
            // $pilot->profile->delete();
        });

    }

    public function scopeTenant($query, $id)
    {
        return $query->where('tenant_id', $id);
    }

    public function welcome(){

        return $this->belongsTo(PlayMedia::class, 'greeting');
    }

    public function module(){

        return $this->morphTo();
    }

    public function flow(){

    	return $this->belongsTo(CallFlow::class, 'call_flow_id');
    }

    public function call_flow(){

    	return $this->belongsTo(CallFlow::class)->withDefault();
    }

    public function tenant(){

    	return $this->belongsTo(Tenant::class);
    }

    public function auto_attendants(){

        return $this->hasMany(AutoAttendant::class, 'pilot_line_id', 'id')->orderBy('order', 'asc');
    }

    public function inbox(){

        return $this->belongsTo(VoicemailInbox::class, 'voicemail_inbox_id');
    }


}
