<?php

namespace App\Http\Controllers\App\Tenant\Media;

use App\Http\Controllers\Controller;

use App\Models\CallRoute;
use App\Models\CallRouteAction;
use App\Models\Extension; 
use App\Models\GroupCall; 
use App\Models\Number; 
use App\Models\PlayMedia; 
use App\Models\VirtualReceptionist as Receptionist;

use App\Repos\CallFlowRepo;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CallFlowController extends Controller
{
     public function __construct(CallFlowRepo $callFlowRepo){

        $this->middleware(['tenant', 'auth']);
        $this->callFlowRepo = $callFlowRepo;
    }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($domain, Request $request)
    {

        $txttosp = PlayMedia::company()->where('application', 'tts')->get();
        $sounds = PlayMedia::company()->where('application', 'file')->where('exist', 1)->where('error', 0)->get();
      
        $extens = Extension::company()->where('type', 'sip_profile')->orderBy('name')->get();
        $groups = GroupCall::company()->orderBy('name')->get();
        $numbers = Number::company()->get();

        $receptionists = Receptionist::company()->orderBy('name')->get(); 
        $call_routes = CallRoute::company()->get();
        
        return view('app.tenant.media-services.call_flow.index', compact('call_routes', 'txttosp', 'sounds', 'numbers', 'groups', 'extens', 'receptionists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $txttosp = PlayMedia::company()->where('application', 'tts')->get();
        $sounds = PlayMedia::company()->where('application', 'file')->where('exist', 1)->where('error', 0)->get();
      
        $extens = Extension::company()->where('type', 'sip_profile')->orderBy('name')->get();
        $groups = GroupCall::company()->orderBy('name')->get();
        $numbers = Number::company()->get();

        $receptionists = Receptionist::company()->orderBy('name')->get();     
      
        return view('app.tenant.media-services.call_flow.create', compact('call_routes', 'txttosp', 'sounds', 'numbers', 'groups', 'extens', 'receptionists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($domain, Request $request)
    {
        $this->validate($request, [
            'title' => ['required', Rule::unique('call_routes', 'title')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                }), 'min:3', 'max:125'],
            'greeting' => 'required',
            'destination_type' => 'required',
            'destination' => 'required',
            'destination_value' => 'required',
            ]);

        
        $tenant = TenantManager::get();

        $this->callFlowRepo->store($request->all());

        return redirect()->route('tenant.media-service.call-flow.index', [$tenant->domain])->with('flash_message', 'Call flow successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain, $id)
    {
        $call_route = CallRoute::company()->where('id',$id)->get()->first();
        $call_routes = CallRoute::company()->get();
        
        if(!$call_route){

            abort(404);
        }
      
        return view('app.tenant.media-services.call_flow.show', compact('call_route'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $call_routes = CallRoute::company()->get();
        $txttosp = PlayMedia::company()->where('application', 'tts')->get();
        $sounds = PlayMedia::company()->where('application', 'file')->get();
      
        $extens = Extension::company()->where('type', 'sip_profile')->orderBy('name')->get();
        $groups = GroupCall::company()->orderBy('name')->get();
        $numbers = Number::company()->get();

        $receptionists = Receptionist::company()->orderBy('name')->get();   
      
        return view('app.tenant.call_flow.index', compact('call_routes', 'txttosp', 'sounds', 'numbers', 'groups', 'extens', 'receptionists'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain)
    {
        $this->validate($request, [
            'route_id' => 'required|exists:call_routes,id',
            'title' => ['required', Rule::unique('call_routes', 'title')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })->ignore($request->route_id), 'min:3', 'max:125'],
            'greeting' => 'required',
            'destination_type' => 'required',
            'destination' => 'required',
            'destination_value' => 'required',
            ]);
        
        $tenant = TenantManager::get();

        $this->callFlowRepo->update($request->all());

        return redirect()->route('tenant.media-service.call-flow.index', [$tenant->domain])->with('flash_message', 'Call flow successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($domain, Request $request)
    {
        $this->validate($request, [    
            'route_id' => 'required|exists:call_routes,id', 
            ]); 
        $tenant = TenantManager::get();

        $route = CallRoute::find($request->route_id);
        if(count($route->auto_attendants) > 0 ){

            return redirect()->route('tenant.media-service.call-flow.index', [$tenant->domain])->withErrors(['Unable to delete call flow. Ensure it is not use by any Auto Attendant']);
        }

        $route->delete();

        return redirect()->route('tenant.media-service.call-flow.index', [$tenant->domain])->with('flash_message', 'Time Schedule Successfully Deleted');
    }
}
