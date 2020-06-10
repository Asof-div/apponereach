<?php 

namespace App\Repos;

use App\Models\Number;
use App\Models\CallFlow;
use App\Models\CallRoute;
use App\Models\Extension;
use App\Models\PilotLine;
use App\Models\PlayMedia;
use App\Models\User;
use App\Models\VirtualReceptionistMenu;

class NumberRepo extends DestinationRepo
{



	public function store($user, $data){

        $tenant = $user->tenant;

        $number = Number::create([
            'number' => $data['number'],
            'tenant_id' => $tenant->id,
            'code' => $tenant->code,
            'name' => $data['name'],
            'user_id' => $data['user_id'],
            'context' => "ORE_".$tenant->code,
            'status' => false,
        ]);

	}

    public function update($user, $number, $data){
       
        $number->update([
            'name' => $data['name'],
            'number' => $data['number'],
            'user_id' => $data['user_id'],
            'status' => false,
        ]);

    }

    public function delete($number){

        $number->delete();
    }

    public function deletable($number, &$destination){


        $pilot_lines = PilotLine::company($number->tenant_id)->where('module_type', 'App\Models\Number')->where('module_id', $number->id)->get();
        if( count($pilot_lines) > 0){
            $destination = 'Pilot number';
            return false;
        }

        $routes = CallRoute::company($number->tenant_id)->where('module_type', 'App\Models\Number')->where('module_id', $number->id)->get();
        if( count($routes) > 0){
            $destination = 'Automated Route';
            return false;
        }

        $menus = VirtualReceptionistMenu::company($number->tenant_id)->where('module_type', 'App\Models\Number')->where('module_id', $number->id)->get();
        if( count($menus) > 0){
            $destination = 'IVR';
            return false;
        }

        if( count($number->groups) > 0){
            $destination = 'Group Call';
            return false;
        }

        return true;
    }


}