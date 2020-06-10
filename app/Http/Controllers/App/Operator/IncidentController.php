<?php

namespace App\Http\Controllers\App\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\Operator;
use App\Models\Admin;

class IncidentController extends Controller
{
    public function __construct(){

        $this->middleware('auth:operator');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incidents = Incident::orderBy('name', 'asc')->paginate(50);

        return view('app.operator.incident.index', compact('incidents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $operators = Operator::get();
        $admins = Admin::get();

        return view('app.operator.incident.create', compact('operators', 'admins'));
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
            'name' => 'required|min:3|max:250|unique:incidents,name',
            'label' => 'required|min:3|max:250',
            'initial_response_time' => 'required|numeric',
            'expected_resolution_time' => 'required|numeric',
            'escalation_interval_time' => 'required|numeric',
            'priority' => 'required',
            'severity' => 'required',
            'lead_operator' => 'required|exists:operators,id',
            'lead_admin' => 'required|exists:admins,id',
            ]);

        $incident = Incident::create([
            'name' => $request->name,
            'label' => $request->label,
            'initial_response' => $request->initial_response_time,
            'initial_response_unit' => $request->initial_response_unit,
            'expected_resolution' => $request->expected_resolution_time,
            'expected_resolution_unit' => $request->expected_resolution_unit,
            'escalation_interval' => $request->escalation_interval_time,
            'escalation_interval_unit' => $request->escalation_interval_unit,
            'priority' => $request->priority,
            'severity' => $request->severity,
            'apply_to_user' => $request->apply_to_user ? 1 : 0,
            'operator_id' => $request->lead_operator,
            'admin_id' => $request->lead_admin,
            ]);

        return redirect()->route('operator.incident.index')->with('flash_message', 'Incident Successfully Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Incident $incident)
    {

        $admins = Admin::whereNotIn('id', $incident->admins->pluck('id')->toArray())->orderBy('lastname')->get();
        $operators = Operator::whereNotIn('id', $incident->operators->pluck('id')->toArray())->orderBy('lastname')->get();

        return view('app.operator.incident.show', compact('incident', 'operators', 'admins'));
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
    public function update(Request $request, Incident $incident)
    {
        $this->validate($request, [
            'incident_id' => 'required|exists:incidents,id',
            'name' => 'required|min:3|max:250|unique:incidents,name,'.$incident->id,
            'label' => 'required|min:3|max:250',
            'initial_response_time' => 'required|numeric',
            'expected_resolution_time' => 'required|numeric',
            'escalation_interval_time' => 'required|numeric',
            'priority' => 'required',
            'severity' => 'required',
            'lead_operator' => 'required|exists:operators,id',
            // 'lead_admin' => 'required|exists:admins,id',
            ]);

        $incident->update([
            'name' => $request->name,
            'label' => $request->label,
            'initial_response' => $request->initial_response_time,
            'initial_response_unit' => $request->initial_response_unit,
            'expected_resolution' => $request->expected_resolution_time,
            'expected_resolution_unit' => $request->expected_resolution_unit,
            'escalation_interval' => $request->escalation_interval_time,
            'escalation_interval_unit' => $request->escalation_interval_unit,
            'priority' => $request->priority,
            'severity' => $request->severity,
            'apply_to_user' => $request->apply_to_user ? 1 : 0,
            'operator_id' => $request->lead_operator,
            'admin_id' => $request->lead_admin,

            ]);

        return redirect()->route('operator.incident.show', [$incident->id])->with('flash_message', 'Incident Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Incident $incident)
    {
         $incident->delete();

        return redirect()->route('operaror.incident.index')->with('flash_message', 'Incident has been deleted');
    }

    public function assignToOperator(Incident $incident, Request $request)
    {
        $this->validate($request, [
            'operator' => 'required|exists:operators,id',
        ]);

        $incident->assignToOperator((int)$request->operator);

        return redirect()->route('operator.incident.show', [$incident->id])->with('flash_message', 'Assigned Operator');
    }

    public function revokeFromOperator(Incident $incident, Request $request)
    {
        $this->validate($request, [
            'operator' => 'required|exists:operators,id',
        ]);

        $incident->revokeFromOperator((int)$request->operator);

        return redirect()->route('operator.incident.show', [$incident->id])->with('flash_message', 'Role has been removed from account');
    }



    public function assignToAdmin(Incident $incident, Request $request)
    {
        $this->validate($request, [
            'admin' => 'required|exists:admins,id',
        ]);

        $incident->assignToAdmin((int)$request->admin);

        return redirect()->route('operator.incident.show', [$incident->id])->with('flash_message', 'Assigned Admin');
    }

    public function revokeFromAdmin(Incident $incident, Request $request)
    {
        $this->validate($request, [
            'admin' => 'required|exists:admins,id',
        ]);

        $incident->revokeFromAdmin((int)$request->admin);

        return redirect()->route('operator.incident.show', [$incident->id])->with('flash_message', 'Role has been removed from account');
    }

}
