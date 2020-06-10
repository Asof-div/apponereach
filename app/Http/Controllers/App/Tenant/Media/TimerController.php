<?php

namespace App\Http\Controllers\App\Tenant\Media;

use App\Http\Controllers\Controller;

use App\Models\Timer;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TimerController extends Controller
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
        
        $timers = Timer::company()->get();
            
        return view('app.tenant.media-services.timer.index', compact('timers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('app.tenant.media-services.timer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', Rule::unique('timers', 'title')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                }), 'min:4'], 
            'period' => 'required', 
            'start_time' => 'required',
            'end_time' => 'required',
            ]);
        
        $tenant = TenantManager::get();


        Timer::create([
            'title' => $request->title,
            'days' => $request->weekdays,
            'period' => $request->period,
            'start_mon' => $request->start_mon,
            'start_day' => $request->start_day,
            'end_day' => $request->end_day,
            'start_time' => $request->start_time,
            'custom_day' => $request->custom_day,
            'end_time' => $request->end_time,
            'tenant_id' => $tenant->id,
       
            ]);

        

        return redirect()->route('tenant.media-service.timer.index', [$tenant->domain])->with('flash_message', 'Time Schedule Successfully Added');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain, $id)
    {
        $timer = Timer::company()->where('id', $id)->get()->first();

        if(!$timer){

            abort(404);
        }

        return view('app.tenant.media-services.timer.show', compact('timer'));
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
        
        $this->validate($request, [
            'title' => ['required', Rule::unique('timers', 'title')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })->ignore($request->timer_id), 'min:4'],
            'timer_id' => 'required|exists:timers,id', 
            'period' => 'required', 
            'start_time' => 'required',
            'end_time' => 'required',
            ]);
        
        $tenant = TenantManager::get();

        $timer = Timer::find($request->timer_id);
        $timer->update([
            'title' => $request->title,
            'days' => $request->weekdays,
            'period' => $request->period,
            'start_mon' => $request->start_mon,
            'start_day' => $request->start_day,
            'end_day' => $request->end_day,
            'start_time' => $request->start_time,
            'custom_day' => $request->custom_day,
            'end_time' => $request->end_time,
       
            ]);

        return redirect()->route('tenant.media-service.timer.show', [$tenant->domain, $timer->id])->with('flash_message', 'Time Schedule Successfully Updated');
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
            'timer_id' => 'required|exists:timers,id', 
            ]); 
        $tenant = TenantManager::get();

        $timer = Timer::find($request->timer_id);
        if(count($timer->auto_attendants) > 0 ){

            return redirect()->route('tenant.media-service.timer.show', [$tenant->domain, $timer->id])->withErrors(['Unable to delete time schedule. Ensure it is not use by any Auto Attendant']);
        }

        $timer->delete();

        return redirect()->route('tenant.media-service.timer.index', [$tenant->domain])->with('flash_message', 'Time Schedule Successfully Deleted');
    }
}
