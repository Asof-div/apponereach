<?php 
namespace App\Services;

use App\Models\Billing;
use App\Services\PaymentGateway\PayStackBillingService as PayStack;
use App\Models\Number;
use App\Models\PilotLine;
use App\Models\Operator\CallFlow;

use App\Models\Tenant;
use DB;

class ManageCUGNumber {


	public function __construct($tenant=null){

        $this->tenant = $tenant;
        $this->payStack = new PayStack;
	}




    public function add($data) : Array {
        
        $tenant = Tenant::find($data['tenant_id']);
        $this->tenant = $tenant;
        $slot = (int) $tenant->info->extra_number + (int) $tenant->package->msisdn_limit;
        if($tenant->info->number_lock == 0){
            try{
                $resolve = ['error' => ['No slot available.'], 'status' => 'error'];
                DB::beginTransaction();

                $tenant->info->update(['number_lock' => 1]);
                $all_numbers = Number::company($data['tenant_id'])->get();

                if($all_numbers->count() < $slot ){

                    $resolve = $this->storeAndGenerateCallFlow($tenant, array_merge($data, ['status' => 1, 'msg' => 'Success'])); 

                }

                $tenant->info->update(['number_lock' => 0]);

                DB::commit();
                return $resolve;

            }catch(\Exception $e){
                DB::rollback();
                return ['error' => ['Cant not complete acction.'], 'status' => 'error'];

            }
           

        }else{

            return ['numbers' => Number::company($data['tenant_id'])->get(), 'error' => ['Another user is current performing the same action. Please wait or refresh your page.']];
        }
            
        return ['numbers' => Number::company($data['tenant_id'])->where('status_msg', 'Success')->where('status', 1)->get(), 'status' => 'exceed_limit'];

    }

    public function update($data) : Array {
        $tenant = Tenant::find($data['tenant_id']);
        $this->tenant = $tenant;
        $numbers = Number::company($data['tenant_id'])->get();

        $this->updateAndGenerateCallFlow($tenant, array_merge($data, ['status' => 1, 'msg' => 'Success']));    

        return ['numbers' => Number::company($data['tenant_id'])->where('status_msg', 'Success')->where('status', 1)->get(), 'status' => 'success', 'success' => 'Number Successfully Updated' ];

        
    }

    public function numberValidation($number){


        return true;
    }


    public function storeAndGenerateCallFlow($tenant, $data){

        $number = Number::create([
            'name' => $data['name'],
            'number' => $data['number'],
            'tenant_id' => $tenant->id,
            'code' => $tenant->code,
            'user_id' => $data['user_id'],
            'context' => "USER_".$tenant->code,
            'status' => 1 ,
            'slot' => isset($data['slot']) ? 1 : 0,
            'status_msg' => isset($data['msg']) ? $data['msg'] : null ,
        ]);


        $flow = CallFlow::create([
            'tenant_id' =>  $number->tenant_id,
            'context' =>  $number->context,
            'code' =>  $number->context,
            'direction' =>  'inbound', //intercom, outbound, inbound, test - extension 
            'dial_string' => $number->number, 
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
            'dest_type' => 'Number',
            'dest_id' => $number->id,            
            'dest_params' => json_encode([
                'action' => 'bridge',
                'value' => $number->number,

                ]),

            ]);

        $number->update([
            'call_flow_id' => $flow->id,
            ]);

        return ['success'=>'Number Successfully Added', 'status' => 'success', 'numbers' => Number::company($data['tenant_id'])->get()]; 

    }

    public function updateAndGenerateCallFlow($tenant, $data){

        $number = Number::find($data['number_id']);
        if($number){


            $number->update([
                'name' => $data['name'],
                'number' => $data['number'],
                'user_id' => $data['user_id'],
                'context' => "USER_".$tenant->code,
                'status' => 1 ,
                'status_msg' => isset($data['msg']) ? $data['msg'] : null ,
                ]);

            $flow = $number->call_flow;
            if($flow){

                $flow->update([
                    'dial_string' => $number->number, 
                    'conditions' => 'destination',
                    'active' => $tenant->status(),
                    'dest_type' => 'Number',
                    'dest_id' => $number->id,            
                    'dest_params' => json_encode([
                        'action' => 'bridge',
                        'value' => $number->id,
                        ]),
                    
                    ]);
            }
            else{

                $flow = CallFlow::create([
                    'tenant_id' =>  $number->tenant_id,
                    'context' =>  $number->context,
                    'code' =>  $number->context,
                    'direction' =>  'inbound', //intercom, outbound, inbound, test - extension 
                    'dial_string' => $number->number, 
                    'conditions' => 'destination',
                    'wday' => '',
                    'mon' => '',
                    'start_day' => '',
                    'end_day' => '',
                    'start_time' => '',
                    'end_time' => '',
                    'custom_day' => '',
                    'active' => $tenant->status,
                    'send_to_voicemail' => '0',
                    'voicemail_number' => '',
                    'record_call' => 0,
                    'dest_type' => 'Number',
                    'dest_id' => $number->id,            
                    'dest_params' => json_encode([
                        'action' => 'bridge',
                        'value' => $number->number,

                        ]),

                    ]);

                $number->update([
                    'call_flow_id' => $flow->id,
                    ]);
    
                return ['success'=>'Number Successfully Updated.', 'status' => 'success', 'numbers' => Number::company($data['tenant_id'])->get()]; 

            }

        }


    }


    public function delete($data) : Array{

        $number = Number::find($data['number_id']);

        if($number){
            $flow = $number->call_flow;
            $flow->delete();
            $number->delete();

            return ['status' => ['succes' => 'Number Successfully Deleted.' ], 'code' => 200];
        }

        return ['status' => [ 'error' => ['Unable to delete number.'] ], 'code' => 422];
    }

    public function deletable($number){

        $call_flows = CallFlow::company()->where('dial_string', '<>', $number->number)->where('dest_type', 'Number')->where('dest_id', $number->id)->get();
        if(count($call_flows) > 0 ){
            return false;
        }

        $routes = CallRoute::where('module_type', 'App\Models\Number')->where('module_id', $number->id)->get();
        if( count($routes) > 0){
            return false;
        }

        $menus = VirtualReceptionistMenu::company()->where('module_type', 'App\Models\Number')->where('module_id', $number->id)->get();
        if( count($menus) > 0){
            return false;
        }

        if( count($number->groups) > 0){

            return false;
        }

        return true;
    }




}