<?php

namespace App\Http\Controllers\App\Tenant\Conference;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\PlayMedia;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use Illuminate\Validation\Rule;

use Validator;
use Gate;

class AudioConferenceController extends Controller
{

    public function __construct(){

        $this->middleware(['tenant', 'auth']);
    
    }    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $conferences = Conference::company()->get();

        return view('app.tenant.conference.audio_conference.index', compact('conferences'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->conferences = Conference::company()->get();
        $conferences = $this->conferences;
        $sounds = PlayMedia::company()->where('application', 'file')->get();

        $conf_num = $this->generate();
        $admin_pin ="23456";
        $user_pin ="1234";


        return view('app.tenant.conference.audio_conference.create', compact('conferences', 'conf_num', 'admin_pin', 'user_pin', 'sounds'));
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

            'name' => ['required', 'string', Rule::unique('conferences', 'bridge_name')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })],
            'number' => ['required', 'numeric', Rule::unique('conferences')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                }), 'min:10', 'max:999' ],
        ]);

        $tenant = TenantManager::get();
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 422);
        }
        
        $conferences = Conference::company()->get();
        if ($conferences->count() >= 3 && !Gate::check('private_meeting_room') ) {
            return response()->json(['error'=>['Sorry you have exceed the maximum number of conference room for your package.']], 422);
        }

        Conference::create([
            'tenant_id' => $tenant->id,
            'code' => $tenant->code,
            'context' => "USER_".$tenant->code,
            'number' => sprintf("%03d", $request->number),
            'bridge_name' => $request->name,
            'admin_pin' => $request->type == 'Local' ? null : $request->moderator_pin ,
            'guest_pin' => $request->user_pin,
            'type' => $request->type,
            'enable_audio' => 1,
            'enable_video' => 0,
            'record' => $request->has('auto_record') ? 1 : 0,
            'wait_for_admin' => $request->has('wait') ? 1 : 0,
            'announce_join_leave' => $request->has('announce') ? 1 : 0
        
        ]);


        return response()->json(['success'=>'Conference Successfully Saved.']  , 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain, $number)
    {
        $conference = Conference::company()->where('number', $number)->get()->first() ?? abort(404);
        
        return view('app.tenant.conference.audio_conference.show', compact('conference'));
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
            'conference_id' => 'required|exists:conferences,id',
            'name' => ['required', 'string', Rule::unique('conferences','bridge_name')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })->ignore($request->conference_id)],
            'number' => ['required', 'numeric', Rule::unique('conferences')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })->ignore($request->conference_id), 'min:10', 'max:999' ],
        ]);

        $tenant = TenantManager::get();
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 422);
        }
        
        $conference = Conference::find($request->conference_id);
        if($conference){
            $conference->update([
                'tenant_id' => $tenant->id,
                'code' => $tenant->code,
                'context' => "USER_".$tenant->code,
                'number' => sprintf("%03d", $request->number),
                'bridge_name' => $request->name,
                'admin_pin' => $request->type == 'Local' ? null : $request->moderator_pin ,
                'guest_pin' => $request->user_pin,
                'type' => $request->type,
                'enable_audio' => 1,
                'enable_video' => 0,
                'record' => $request->has('auto_record') ? 1 : 0,
                'wait_for_admin' => $request->has('wait') ? 1 : 0,
                'announce_join_leave' => $request->has('announce') ? 1 : 0
            
            ]);
            return response()->json(['success'=>'Conference Successfully Saved.']  , 200);
        }

        return response()->json(['error'=>['Unable to update conference.']]  , 422);
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
            'conference_id' => 'required|exists:conferences,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 422);
        }
        
        $conference = Conference::find($request->conference_id);
        if($conference){
            $conference->delete();
            return response()->json(['success'=>'Conference Successfully deleted.']  , 200);
        }

        return response()->json(['error'=>['Unable to delete conference.']]  , 422);
    }

    public function generate(){
        

        $result = sprintf("%03d", rand(10, 999));
        if($this->conferences->where('number', $result)->first()){

            $this->generate();
        }

        return $result;
    }



    public function check(Request $request, $conf, $pin){
        
        $conf = Conference::company()->where('number', $conf)->first();

        if($conf){

            if($conf->admin_pin == $pin){

                return $conf->admin_profile;
            }elseif ($conf->user_pin == $pin) {
                
                return $conf->user_profile;
            }else{

                return "No";
            }

        }else{

            return "No";
        }

    }

}
