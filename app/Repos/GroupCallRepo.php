<?php 

namespace App\Repos;

use App\Models\CallFlow;
use App\Models\CallRoute;
use App\Models\GroupCall;
use App\Models\GroupMember;
use App\Models\PilotLine;
use App\Models\PlayMedia;
use App\Models\User;
use App\Models\VirtualReceptionistMenu;
use App\Models\VoicemailInbox;

use Auth;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;

class GroupCallRepo extends DestinationRepo
{

	public function __construct(){

	}

	public function store($data){

        $user = Auth::user();
        $tenant = $user->tenant;

        $members = $data['members'];
 
        
        $group = GroupCall::create([
            'name' => $data['name'],
            'number' => $data['extension'],
            'tenant_id' => $tenant->id,
            'code' => $tenant->code,
            'context' => $tenant->code,
            'call_strategy' => $data['call_strategy'],
            'members' => $members,
            ]);


        $flow = CallFlow::create([
            'tenant_id' =>  $group->tenant_id,
            'context' =>  $group->context,
            'code' =>  $group->context,
            'title' => $group->name,
            'direction' =>  'intercom', //intercom, outbound, inbound, test - extension 
            'dial_string' => $group->number, 
            'conditions' => 'destination',
            'wday' => '',
            'mon' => '',
            'start_day' => '',
            'end_day' => '',
            'start_time' => '',
            'end_time' => '',
            'custom_day' => '',
            'active' => $tenant->status(),
            'send_to_voicemail' => '0',
            'voicemail_number' => '',
            'record_call' => 0,
            'dest_type' => 'Group',
            'dest_id' => $group->id,            
            'dest_params' => json_encode([
                'action' => 'bridge',
                'value' => $members,

                ]),

            ]);

        $group->update([
            'call_flow_id' => $flow->id,
            ]);

        $this->generateMembers($members, $group);

	}

    public function update($group, $data){

        
        $user = Auth::user();
        $tenant = $user->tenant;

        $members = $data['members'];
 
        $group->update([
            'name' => $data['name'],
            'number' => $data['extension'],
            'context' => $tenant->code,
            'call_strategy' => $data['call_strategy'],
            'members' => $members,
            ]);

        $flow = $group->call_flow;
        if($flow){

            $flow->update([
                'dial_string' => $group->number, 
                'conditions' => 'destination',
                'active' => $tenant->status(),
                'title' => $group->name,
                'dest_type' => 'Group',
                'dest_id' => $group->id,            
                'dest_params' => json_encode([
                    'action' => 'bridge',
                    'value' => $members,
                    ]),
                
                ]);
        }
        else{

            $flow = CallFlow::create([
                'tenant_id' =>  $group->tenant_id,
                'context' =>  $group->context,
                'code' =>  $group->context,
                'direction' =>  'intercom', //intercom, outbound, inbound, test - extension 
                'dial_string' => $group->number, 
                'title' => $group->name,
                'conditions' => 'destination',
                'wday' => '',
                'mon' => '',
                'start_day' => '',
                'end_day' => '',
                'start_time' => '',
                'end_time' => '',
                'custom_day' => '',
                'active' => $tenant->status(),
                'send_to_voicemail' => '0',
                'voicemail_number' => '',
                'record_call' => 0,
                'dest_type' => 'Group',
                'dest_id' => $group->id,            
                'dest_params' => json_encode([
                    'action' => 'bridge',
                    'value' => $members,

                    ]),

            ]);

            $group->update([
                'call_flow_id' => $flow->id,
                ]);
        }

        $this->generateMembers($members, $group);
    }

    private function generateMembers($members, $group){
        $gmembers = GroupMember::where('group_id', $group->id)->get();
        foreach ($gmembers as $gmember) {
            $gmember->delete();
        }

        foreach ($members as $key => $member) {
            $this->setDestination($member['member_type']);
            GroupMember::create([
                'tenant_id' => $group->tenant_id,
                'group_id' => $group->id,
                'member_id' => $member['member_id'],
                'member_type' => $this->getModuleType(),
                'number' => $member['member_number'],
                'label' => $member['member_label'],
                'type' => $member['member_type'],
                'ordered_list' => $key,
            ]);
        }
    }


    public function delete($group){

        if($group){
            $flow = $group->call_flow;
            if($flow){
                $flow->delete();
            }

            foreach (GroupMember::where('group_id', $group->id)->get() as $member) {
                $member->delete();
            }

            $group->delete();

        }

    }

    public function deletable($group, &$destination){
        
        if(!$group){ return false; }

        $routes = CallRoute::company($group->tenant_id)->where('module_type', 'App\Models\GroupCall')->where('module_id', $group->id)->get();
        if(count($routes) > 0){
            $destination = "Automated Route";
            return false;
        }

        $menus = VirtualReceptionistMenu::company($group->tenant_id)->where('module_type', 'App\Models\GroupCall')->where('module_id', $group->id)->get();
        if(count($menus) > 0){
            $destination = "IVR";
            return false;
        }

        $pilot_lines = PilotLine::company($group->tenant_id)->where('module_type', 'App\Models\GroupCall')->where('module_id', $group->id)->get();
        if(count($pilot_lines) > 0){
            $destination = "Pilot Number";
            return false;
        }

        return true;
    }





}