<?php

namespace App\Http\Controllers\App\Tenant\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Events\PilotNumberDestination;

use App\Helpers\DestinationHelper; 

use App\Models\CallRoute;
use App\Models\Extension;
use App\Models\GroupCall;
use App\Models\Number;
use App\Models\PilotLine;
use App\Models\PlayMedia;
use App\Models\Timer;
use App\Models\VirtualReceptionist as Receptionist;
use App\Models\VirtualReceptionistMenu as ReceptionistMenu;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use Validator;

class PilotLineController extends Controller
{

    public function __construct(){

        $this->middleware(['tenant','auth']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $pilot_lines = PilotLine::tenant(TenantManager::getTenantKey())->get();
        $pilot_lines = PilotLine::company()->get();
        return view('app.tenant.media-services.pilot_line.index', compact('pilot_lines'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain, $id)
    {
        $pilot_line = PilotLine::company()->where('number',$id)->get()->first() ?? abort(404);
        $extens = Extension::company()->where('type', 'sip_profile')->orderBy('name')->get();
        $groups = GroupCall::company()->orderBy('name')->get();
        $numbers = Number::company()->get();
        $txttosp = PlayMedia::company()->where('application', 'tts')->get();
        $sounds = PlayMedia::company()->where('application', 'file')->get();       
        $receptionists = Receptionist::company()->orderBy('name')->with('menus')->get();   
        $pilot_lines = PilotLine::company()->get();
        $call_routes = CallRoute::company()->get();
        $timers = Timer::company()->get();


        $destination = new DestinationHelper($pilot_line->module);


        return view('app.tenant.media-services.pilot_line.config', compact('pilot_line', 'pilot_lines', 'extens', 'numbers', 'groups', 'receptionists', 'call_routes', 'timers', 'sounds', 'txttosp', 'destination') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $pilot_line
     * @return \Illuminate\Http\Response
     */
    public function edit($domain, $pilot_line)
    {
        $pilot_line = PilotLine::company()->where('number',$pilot_line)->get()->first() ?? abort(404);
        $pilot_lines = PilotLine::company()->get(); 
       

        return view('app.tenant.media-services.pilot_line.edit', compact('pilot_line', 'pilot_lines') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function defaultRoute($domain, Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            
            'pilot_line' => 'required|exists:pilot_lines,id',
            'destination' => 'required',
            'destination_type' => 'required',
            'greeting' => 'required',

        ]);


        if ($validator->passes()) {

            
            event(new PilotNumberDestination($request->all()));


            return response()->json(['success' => 'Call Flow To Destination Successfully Updated']);

        }


        return response()->json(['error'=>$validator->errors()->all()], 422);
            
    }


    public function voicemail($domain, Request $request){

        $validator = Validator::make($request->all(), [
            
            'pilot_line' => 'required|exists:pilot_lines,id',
            'email' => 'required_if:send_to_email,on',

        ]);

        if($validator->passes()){

            $pilot_line = PilotLine::find($request->pilot_line) ?? abort(404);

            $pilot_line->update([
                    'voicemail_email' => $request->email,
                    'send_voicemail_to_email' => $request->send_to_email ? 1 : 0,
                    'save_voicemail' => $request->web_portal ? 1 : 0,
                ]);
            if($request->ajax()){

                return response()->json(['success'=>'Voicemail Settings Successfully Saved.']  );
            }

        }

        return response()->json(['error'=>$validator->errors()->all()], 422);
    }


    public function recording($domain, Request $request){

        $validator = Validator::make($request->all(), [
            
            'pilot_line' => 'required|exists:pilot_lines,id',
            'period' => 'required', 
            'start_time' => 'required',
            'end_time' => 'required',
        ]);


        if($validator->passes()){

            $pilot_line = PilotLine::find($request->pilot_line) ?? abort(404);

            $pilot_line->update([
                    'recording_period' => $request->period,
                    'recording_start' => $request->start_time,
                    'recording_end' => $request->end_time,
                    'recording_days' => $request->weekdays,

                ]);
            if($request->ajax()){

                return response()->json(['success'=>'Recording Settings Successfully Saved.']  );
            }

        }

        return response()->json(['error'=>$validator->errors()->all()], 422);

     }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }




}