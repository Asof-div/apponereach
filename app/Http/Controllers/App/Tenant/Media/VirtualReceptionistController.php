<?php

namespace App\Http\Controllers\App\Tenant\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Extension;
use App\Models\GroupCall as GroupRing;
use App\Models\Number;  
use App\Models\PlayMedia;
use App\Models\Conference;
use App\Models\VirtualReceptionist as Receptionist;
use App\Models\VirtualReceptionistMenu as ReceptionistMenu;
use App\Repos\VirtualReceptionistRepo;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use Illuminate\Validation\Rule;
use Validator;

class VirtualReceptionistController extends Controller
{
    public function __construct(){

        $this->middleware(['tenant', 'auth']);
        $this->virtualReceptionistRepo = new VirtualReceptionistRepo();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $receptionists = Receptionist::company()->get();   

        return view('app.tenant.media-services.virtual-receptionist.index', compact('receptionists') );
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $receptionists = Receptionist::company()->get();   
        $extens = Extension::company()->where('type', 'sip_profile')->get();   
        $txttosp = PlayMedia::company()->where('application', 'tts')->get();
        $sounds = PlayMedia::company()->where('application', 'file')->get();
        $numbers = Number::company()->where('status', 1)->get();
        $groups = GroupRing::company()->get();
        $conferences = Conference::company()->where('type', 'Private')->get();

        return view('app.tenant.media-services.virtual-receptionist.create', compact('receptionists', 'extens', 'txttosp', 'sounds', 'groups', 'numbers', 'conferences'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'name' => ['required', Rule::unique('virtual_receptionists', 'name')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                }), 'min:3', 'max:125'],
            'ivr_menu' => 'required',
            'destination' => 'required',
            'ivr_tts' => 'required_if:ivr,yes',

        ]);

        $tenant = TenantManager::get();
        $data_parms = [];

        if ($validator->passes()) {
                    
            if($request->ajax()){

                $this->virtualReceptionistRepo->store($request->all());

            }

            return response()->json([ 'success' => 'Virtual Receptionist Successfully Saved']);

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

        $receptionists = Receptionist::company()->get();   
        $receptionist = Receptionist::company()->where('id', $id)->get()->first();   
        $extens = Extension::company()->where('type', 'sip_profile')->get();   
        $txttosp = PlayMedia::company()->where('application', 'tts')->get();
        $sounds = PlayMedia::company()->where('application', 'file')->get();
        $numbers = Number::company()->where('status', 1)->get();
        $groups = GroupRing::company()->get();
        $conferences = Conference::company()->where('type', 'Private')->get();

        if(!$receptionist){
            abort(404);
        }

        return view('app.tenant.media-services.virtual-receptionist.show', compact('receptionists', 'receptionist', 'extens', 'txttosp', 'sounds', 'groups', 'numbers', 'conferences') );
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
    public function update(Request $request, $domain)
    {
        $validator = Validator::make($request->all(), [
            'receptionist_id' => 'required|exists:virtual_receptionists,id',
            'name' => ['required', Rule::unique('virtual_receptionists', 'name')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })->ignore($request->receptionist_id), 'min:3', 'max:125'],
            'ivr_menu' => 'required',
            'destination' => 'required',
            'ivr_tts' => 'required_if:ivr,yes',

        ]);

        $tenant = TenantManager::get();
        $data_parms = [];

        if ($validator->passes()) {
                    
            if($request->ajax()){

                $this->virtualReceptionistRepo->update($request->all());

            }

            return response()->json([ 'success' => 'Virtual Receptionist Successfully Saved']);

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

            'receptionist_id' => 'required|exists:virtual_receptionists,id',
            'tenant_id' => 'required|exists:tenants,id',

        ]);

        $tenant = TenantManager::get();

        $receptionist = Receptionist::company()->where('id', $request->receptionist_id)->get()->first();

        if(!$receptionist){ response()->json(['error'=>['Receptionist not found'] ], 422); }

        if ($validator->passes()) {

            if($request->ajax()){
                
                if($this->virtualReceptionistRepo->deletable($receptionist)){
                   
                    $result = $this->virtualReceptionistRepo->delete($request->all());

                    return response()->json($result['status'], $result['code']);                                        
                }
                
                return response()->json(['error'=>['Receptionist is currently in use'] ], 422);
            }

        }


        return response()->json(['error'=>$validator->errors()->all()], 422);
    }
    



}
