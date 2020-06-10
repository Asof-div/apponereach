<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\TenantOfficialContact;
use App\Traits\ActivityLog;

class TenantInfo extends Model
{
    use ActivityLog;

    protected $dates = ['registration_date', 'activation_date', 'created_at', 'updated_at'];

    protected static $logs = ['auto_rebill' => ['label' => 'Auto Renew', 'type' => 'boolean', 'display' => 'true']
    ];
    
    protected $guarded = ['id'];

    public function tenant(){

    	return $this->belongsTo(Tenant::class);
    }

    public function updater(){

    	return $this->belongsTo(User::class, 'updated_by')->withDefault([
            'lastname' => '',
        ]);
    }

    public function official(){
        
        return new TenantOfficialContact( unserialize($this->official_info) );
    }

    public function officialToJSON(){
        $json = $this->official();

        return json_encode($json);
    }


}
