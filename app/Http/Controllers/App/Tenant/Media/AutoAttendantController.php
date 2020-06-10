<?php

namespace App\Http\Controllers\App\Tenant\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Events\PilotNumberDestination;

use App\Models\CallRoute;
use App\Models\PilotLine;
use App\Models\PlayMedia;
use App\Models\AutoAttendant;

use App\Repos\AutoAttendantRepo;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use Validator;

class AutoAttendantController extends Controller
{
  
    public function __construct(){
        
        $this->middleware(['tenant','auth']);
        $this->autoAttendantRepo = new AutoAttendantRepo();

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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($domain, $pilot, Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            
            'timers.*' => 'required',
            'titles.*' => 'required',
            'routes.*' => 'required',
            'pilot_line' => 'required',
            // 'configured' => 'required',

        ]);

        $tenant = TenantManager::get();

        if ($validator->passes()) {
                    
            if($request->ajax()){

                $this->autoAttendantRepo->store($request->all());                

            }

            return response()->json([ 'success' => 'Auto Attendant Successfully Saved']);

        }


        return response()->json(['error'=>$validator->errors()->all()], 422);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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
