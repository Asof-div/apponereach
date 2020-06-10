<?php

namespace App\Listeners;

use App\Events\PilotNumberDestination;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Extension;
use App\Models\GroupCall;
use App\Models\Number;  
use App\Models\PilotLine;
use App\Models\PlayMedia;
use App\Models\VirtualReceptionist as Receptionist;
use App\Models\VirtualReceptionistMenu as ReceptionistMenu;

use App\Models\CallFlow;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

class PilotGenerateDestination
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PilotNumberDestination $event)
    {
        
        $param = substr($event->data['greeting'], 0, 1) == 't' ? $event->data['greeting_tts'] : $event->data['sound_path']; 
        $application = substr($event->data['greeting'], 0, 1) == 't' ? 'tts' : 'file';


        $pilotline = PilotLine::find($event->data['pilot_line']);
        $this->setDestination($event->data['destination_type']);
        $pilotline->update([
            'module_id' => $event->data['destination'],
            'module_type' => $this->type,
            'destination' => $event->data['destination'],
            'destination_type' => $event->data['destination_type'],
            'destination_display' => $event->data['destination_display'],
            'greeting' => substr($event->data['greeting'], 1),
            'greeting_type' => $application,
            'greeting_param' => $param,
            'record' => isset($event->data['record']) ? 1 : 0,
            'status' => isset($event->data['status']) ? 1 : 0,
            'voicemail' => isset($event->data['voicemail']) ? 1 : 0,
            ]);

        $this->generateFlow($pilotline);


    }


    private function generateFlow(PilotLine $pilotline){
        $flow = $pilotline->flow;
        $tenant = $pilotline->tenant;
        if($flow){
                    
            $flow->update([
                'dial_string' => $pilotline->number, 
                'conditions' => 'destination',
                'moh' => $pilotline->greeting,
                'moh_type' => $pilotline->greeting_type,
                'moh_param' => $pilotline->greeting_param,
                'active' => $pilotline->status,
                'priority' => 0,
                'send_to_voicemail' => $pilotline->voicemail,
                'voicemail_number' => $pilotline->number,
                'record_call' => $pilotline->record,
                'recording_wday' => json_encode($this->wday($pilotline->recording_days)),
                'recording_period' => $pilotline->recording_period,
                'recording_start' => $pilotline->recording_start,
                'recording_end' => $pilotline->recording_end,
                'dest_type' => ucfirst($pilotline->destination_type),
                'dest_id' => $pilotline->destination,            
                'dest_params' => json_encode([
                    'action' => 'table',
                    'value' => $pilotline->destination,
                    ]),

            ]);

        }else{
            
            
            $flow = CallFlow::create([
                'tenant_id' =>  $tenant->id,
                'context' =>  "USER_".$tenant->code,
                'code' =>  $tenant->code,
                'direction' =>  'inbound', //intercom, outbound, inbound, test - extension 
                'dial_string' => $pilotline->number,
                'conditions' => 'destination',
                'moh' => $pilotline->greeting,
                'moh_type' => $pilotline->greeting_type,
                'moh_param' => $pilotline->greeting_param,
                'wday' => '',
                'mon' => '',
                'start_day' => '',
                'end_day' => '',
                'start_time' => '',
                'end_time' => '',
                'custom_day' => '',
                'active' => $pilotline->status,
                'priority' => 0,
                'send_to_voicemail' => $pilotline->voicemail,
                'voicemail_number' => $pilotline->number,
                'record_call' => $pilotline->record,
                'recording_wday' => json_encode($this->wday($pilotline->recording_days)),
                'recording_period' => $pilotline->recording_period,
                'recording_start' => $pilotline->recording_start,
                'recording_end' => $pilotline->recording_end,
                'dest_type' => ucfirst($pilotline->destination_type),
                'dest_id' => $pilotline->destination,            
                'dest_params' => json_encode([
                    'action' => 'table',
                    'value' => $pilotline->destination,
                    ]),

            ]);

            $pilotline->update(['call_flow_id' => $flow->id]);

        }


    }

    public function setDestination($destination_type){

        switch ( strtolower($destination_type) ) {
            case 'e': 
            case 'extension':
                $type = "App\Models\Extension"; 
                break;
            case 'n':
            case 'number' :
                $type = "App\Models\Number";
                break;
            case 'g':
            case 'group':
                $type = "App\Models\GroupCall";
                break;
            case 'm':
            case 'receptionist':
                $type = "App\Models\VirtualReceptionist";
                break;
            case 's':
            case 'playback':
                $type = "App\Models\PlayMedia";
                break;
            case 'v':
            case 'voicemail':
                $type = "App\Models\PilotLine";
                break;
            case 'c':
            case 'conference':
                $type = "App\Models\Conference";
                break;
            default:
                $type = null;
                break;
        }

        $this->type = $type;

    }

    public function getCondition( $value ){
        $condition = 'destination';

        switch ( strtolower($value) ) {
            case 'e': 
            case 'extension':
            case 'number':
            case 'group':
            case 'receptionist':
            case 'playback':
                $condition = "destination"; 
                break;
            case 'v':
            case 'voicemail':
                $condition = "voicemail";
                break;
            case 'c':
            case 'conference':
                $condition = "conference";
                break;
            default:
                $condition = null;
                break;
        }


        return $condition;
    }



    private function wday($days){

        $binary = str_split( strrev( base_convert($days, 10, 2) ) );

        $wdays = [1, 2, 3, 4, 5, 6, 7];

        $wday = [];

        foreach ($binary as $key => $value) {
            
            if($value == 1){

                $wday[] = $wdays[$key];
            
            }

        }

        return $wday;

    }


}
