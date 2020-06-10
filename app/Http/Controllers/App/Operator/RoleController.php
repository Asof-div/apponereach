<?php

namespace App\Http\Controllers\App\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Operator;
use App\Models\OperatorRole;
use App\Models\OperatorPermission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:operator');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = OperatorRole::orderBy('name')->paginate(20);

        return view('app.operator.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = OperatorPermission::orderBy('name')->get();

        return view('app.operator.role.create', compact('permissions'));
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
            'name' => 'required|string|max:255|unique:operator_roles,name',
            'label' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $role = OperatorRole::create([
            'name' => $request->name,
            'label' => $request->label,
            'description' => $request->description
        ]);

        if($request->has('permissions')){

            foreach ($request->permissions as $permission) {
                $role->addPermission((int)$permission);
            }
        }

        return redirect()->route('operator.role.show', [$role->id])->with('flash_message', 'Role has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OperatorRole $role)
    {
        $role->load('permissions');
        $permissions = OperatorPermission::whereNotIn('id', $role->permissions->pluck('id')->toArray())->orderBy('name')->get();
        $operators = Operator::where('sadmin',0)->whereNotIn('id', $role->users->pluck('id')->toArray())->orderBy('firstname')->get();

        return view('app.operator.role.show', compact('role', 'permissions', 'operators'));
    }

    public function assignPermission(OperatorRole $role, Request $request)
    {
        $this->validate($request, [
            'permission' => 'required|exists:operator_permissions,id'
        ]);

        $role->addPermission((int)$request->permission);

        return redirect()->route('operator.role.show', [$role->id])->with('flash_message', 'Permission has been assigned');
    }

    public function revokePermission(OperatorRole $role, Request $request)
    {
        $this->validate($request, [
            'permission' => 'required|exists:operator_permissions,id'
        ]);

        $role->removePermission((int)$request->permission);

        return redirect()->route('operator.role.show', [$role->id])->with('flash_message', 'Permission has been removed');
    }

    public function assignToAccount(OperatorRole $role, Request $request)
    {
        $this->validate($request, [
            'operator' => 'required|exists:operators,id',
        ]);

        $role->assignToUser((int)$request->operator);

        return redirect()->route('operator.role.show', [$role->id])->with('flash_message', 'Role has been added to account');
    }

    public function revokeFromAccount(OperatorRole $role, Request $request)
    {
        $this->validate($request, [
            'account' => 'required|exists:operators,id',
        ]);

        $role->revokeFromUser((int)$request->account);

        return redirect()->route('operator.role.show', [$role->id])->with('flash_message', 'Role has been removed from account');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OperatorRole $role)
    {

        if ($role->is_default) return redirect()->route('operator.role.show', [$role->id])->with('error', 'You cannot update a system default role.');

        $this->validate($request, [
            'name' => 'required|string|max:255|unique:operator_roles,name,'.$role->id,
            'label' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $role->update([
            'name' => $request->name,
            'label' => $request->label,
            'description' => $request->description
        ]);

        return redirect()->route('operator.role.show', [$role->id])->with('flash_message', 'Role has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(OperatorRole $role)
    {
        if ($role->system) return redirect()->route('operator.role.show', [$role->id])->with('error', 'You cannot delete a system default role.');

        $role->delete();

        return redirect()->route('operator.role.index')->with('flash_message', 'Role has been deleted');
    }
}
