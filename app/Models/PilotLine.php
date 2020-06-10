<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;
use App\Services\PilotDefaultDestination;

use App\Traits\NonGlobalTenantScopeTrait;
use App\Traits\DestinationTrait;

class PilotLine extends Model
{
    use NonGlobalTenantScopeTrait;
    use DestinationTrait;
    
    protected $guarded = ['id'];

    public static function boot() {
        
        parent::boot();

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

    public function greetings(){

        switch (strtolower($this->greeting_type) ) {
            case 'file':
                return $this->welcome ? $this->welcome->title : '';
                break;
            case 'tts':
                return $this->welcome ? $this->welcome->content : '';
                break;
            default:
                # code...
                break;
        }
    }

    public function destination(){

        // switch (strtolower($this->destination_type)) {
        //     case 'extension' :
        //         $label = ;
        //         break;

        //     case 'number' :
        //         $label = 'Ring On A Number';
        //         break;

        //     case 'group' :
        //         $label = 'Ring A Group';
        //         break;

        //     case 'receptionist' :
        //         $label = 'Direct To Virtual Receptionist';
        //         break;

        //     case 'playback' :
        //         $label = 'Play A Custom Message';
        //         break;

        //     case 'voicemail' :
        //         $label = 'Send To Voicemail';
        //         break;

        //     case 'conference' :
        //         $label = 'Join A Private Conference';
        //         break;

        //     default :
        //         $label = '';
        //         break;

        // }
        // return $label;

    }

}
