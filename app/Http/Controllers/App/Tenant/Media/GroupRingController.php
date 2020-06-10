<?php

namespace App\Http\Controllers\App\Tenant\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Extension;
use App\Models\GroupCall as GroupRing;
use App\Models\GroupCallMember;
use App\Models\Number;

use App\Repos\GroupCallRepo;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use Illuminate\Validation\Rule;
use Validator;


class GroupRingController extends Controller
{
    public function __construct(){

        $this->middleware(['tenant', 'auth']);
        $this->groupCallRepo = new GroupCallRepo();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $numbers = Number::company()->where('status', 1)->get();
        $extens = Extension::company()->where('type', 'sip_profile')->get();
        $group_rings = GroupRing::company()->get();

        return view('app.tenant.media-services.group_ring.index', compact('group_rings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
                
        $numbers = Number::company()->where('status', 1)->get();
        $extens = Extension::company()->where('type', 'sip_profile')->get();
        $group_rings = GroupRing::company()->get();

        return view('app.tenant.media-services.group_ring.create', compact('group_rings', 'numbers', 'extens'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($domain, Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'name' => ['required', Rule::unique('group_calls', 'name')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                }), 'min:3', 'max:125'],
            'extension' => ['required', 'numeric', Rule::unique('call_flows', 'dial_string')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                }), 'min:1000', 'max:99999' ],
            'method' => 'required',
            'members' => 'required',
            'phone.*' => 'required',

        ]);

        $tenant = TenantManager::get();
        $data = [];

        if ($validator->passes()) {
                    
            if($request->ajax()){

                $this->groupCallRepo->store($request->all());

            }

            return response()->json([ 'success' => 'GroupRing Successfully Saved']);

        }


        return response()->json(['error'=>$validator->errors()->all()], 422);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain, $id)
    {
        $group = GroupRing::company()->where('number',$id)->get()->first();
        $groups = GroupRing::company()->get();
        $extens = Extension::company()->where('type', 'sip_profile')->get();
        $numbers = Number::company()->where('status', 1)->get();

        if(!$group){

            abort(404);
        }

        return view('app.tenant.media-services.group_ring.show', compact('group', 'groups', 'extens', 'numbers'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($domain, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|exists:group_calls,id',
            'flow_id' => 'required|exists:call_flows,id',
            'name' => ['required', Rule::unique('group_calls', 'name')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })->ignore($request->group_id), 'min:3', 'max:125'],

            'extension' => ['required', 'numeric', Rule::unique('call_flows', 'dial_string')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })->ignore($request->flow_id), 'min:1000', 'max:99999' ],
            
            'method' => 'required',
            'members' => 'required',
            'phone.*' => 'required',

        ]);

        $tenant = TenantManager::get();
        $data = [];

        if ($validator->passes()) {
                    
            if($request->ajax()){
               
                $this->groupCallRepo->update($request->all());

            }

            return response()->json([ 'success' => 'GroupRing Successfully Updated', 'url' => route('tenant.media-service.group-ring.show', [$domain, $request->extension])], 200);

        }


        return response()->json(['error'=>$validator->errors()->all()], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($domain, Request $request)
    {
        $validator = Validator::make($request->all(), [

            'group_id' => 'required|exists:group_calls,id',
            'tenant_id' => 'required|exists:tenants,id',

        ]);

        $tenant = TenantManager::get();

        $group = GroupRing::company()->where('id', $request->group_id)->get()->first();

        if(!$group){ return response()->json(['error'=>['Group not found'] ], 422); }

        if ($validator->passes()) {

            if($request->ajax()){
                
                if($this->groupCallRepo->deletable($group)){
                   
                    $result = $this->groupCallRepo->delete($request->all());

                    return response()->json($result['status'], $result['code']);
                }
                
                return response()->json(['error'=>['Group is currently in use'] ], 422);
            }

        }


        return response()->json(['error'=>$validator->errors()->all()], 422);
    }

}
