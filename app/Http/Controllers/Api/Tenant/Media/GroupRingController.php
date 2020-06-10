<?php

namespace App\Http\Controllers\Api\Tenant\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Extension;
use App\Models\GroupCall;
use App\Models\GroupCallMember;
use App\Models\Number;

use App\Repos\GroupCallRepo;
use App\Services\Response\ApiResponse;

use Illuminate\Validation\Rule;
use Validator;


class GroupRingController extends Controller
{
    public function __construct(){

        $this->middleware(['auth']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $groups = GroupCall::company($request->user()->tenant_id)->get();   

        return response()->json([ 'groupcalls' => $groups], 200);      

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, GroupCallRepo $groupRepo)
    {
        $validator = Validator::make($request->all(), [
            
            'name' => ['required', Rule::unique('group_calls', 'name')->where(function ($query) {
                return $query->where('tenant_id',request()->user()->tenant_id);
                }), 'min:3', 'max:125'],
            'extension' => ['required', 'numeric', Rule::unique('call_flows', 'dial_string')->where(function ($query) {
                return $query->where('tenant_id',request()->user()->tenant_id);
                }), 'digits_between:3,5' ],
            'call_strategy' => 'required',
            'members' => 'required',

        ]);

        if($validator->fails()){
            return response()->json( (new ApiResponse)->error($validator->errors()) , 422);
        }

        $user = $request->user();
        $tenant = $user->tenant;
                    
        // \Log::log('info', $request->all());
        $groupRepo->store($request->all());

        $groups = GroupCall::company($user->tenant_id)->get();   

        return response()->json('ok', 200);      
  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $group = GroupCall::company($request->user()->tenant_id)->where('id',$id)->get()->first();

        if(!$group){
            
            $response = (new ApiResponse)->error('Group Call does not exist');
            return response()->json($response, 403);
        }

        return response()->json(['group' => $group], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request, GroupCallRepo $groupRepo)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', Rule::unique('group_calls', 'name')->where(function ($query) {
                return $query->where('tenant_id',request()->user()->tenant_id);
                })->ignore($id), 'min:3', 'max:125'],

            'extension' => ['required', 'numeric', Rule::unique('call_flows', 'dial_string')->where(function ($query) {
                return $query->where('tenant_id',request()->user()->tenant_id);
                })->ignore($request->flow_id), 'digits_between:3,5' ],
            
            'call_strategy' => 'required',
            'members' => 'required',

        ]);

        if($validator->fails()){
            return response()->json( (new ApiResponse)->error($validator->errors()) , 422);
        }

        $user = $request->user();
        $tenant = $user->tenant;

        $group = GroupCall::company($user->tenant_id)->where('id', $id)->with(['assignedMembers'])->first();   

        if(!$group){
            
            $response = (new ApiResponse)->error('Group Call have already been deleted');
            return response()->json($response, 403);
        }
                    
        $groupRepo->update($group, $request->all());

        $groups = GroupCall::company($user->tenant_id)->get();   

        return response()->json([ 'groups' => $groups], 200); 

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id, Request $request, GroupCallRepo $groupRepo)
    {
        $user = $request->user();
        $tenant = $user->tenant;

        $group = GroupCall::company($user->tenant_id)->where('id', $id)->first();

        if(!$group){ 
            return response()->json( (new ApiResponse)->error('Group Call have already been deleted') , 403);
        }

        $destination = null;
        if(!$groupRepo->deletable($group, $destination)){
            return response()->json( (new ApiResponse)->error('Can not delete Group call used as destination in '. $destination) , 403);
        }
        
        try{

            $groupRepo->delete($group);

            $groups = GroupCall::company($user->tenant_id)->get();   

            return response()->json(['groups' => $groups], 200);
    
        }catch(\Exception $e){

            return response()->json( (new ApiResponse)->error('Unable to delete group call') , 403);
        }

    }

}
