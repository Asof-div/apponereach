<?php

namespace App\Http\Controllers\App\Tenant\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web', 'tenant']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($domain)
    {
        $roles = Role::company()->orderBy('name')->orWhere(function($query){
            $query->where('tenant_id', null)->where('system', true);
        })->paginate(20);

        return view('app.tenant.account.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($domain)
    {
        $permissions = Permission::orderBy('name')->get();

        return view('app.tenant.account.role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $domain)
    {
        $this->validate($request, [
            'tenant_id' => 'required|exists:tenants,id',
            'name' => 'required|string|max:255|unique:roles,name',
            'label' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $role = Role::create([
            'tenant_id' => $request->tenant_id,
            'name' => $request->name,
            'label' => $request->label,
            'description' => $request->description
        ]);

        if($request->has('permissions')){

            foreach ($request->permissions as $permission) {
                $role->addPermission((int)$permission);
            }
        }

        return redirect()->route('tenant.account.role.show', [$domain, $role->id])->with('flash_message', 'Role has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain, Role $role)
    {
        if(!$role->match($domain)){ abort(404); }
        $role->load('permissions');
        $permissions = Permission::whereNotIn('id', $role->permissions->pluck('id')->toArray())->orderBy('name')->get();
        $users = User::where('manager',0)->whereNotIn('id', $role->users->pluck('User_id')->toArray())->orderBy('firstname')->get();

        return view('app.tenant.account.role.show', compact('role', 'permissions', 'users'));
    }

    public function assignPermission($domain, Role $role, Request $request)
    {
        $this->validate($request, [
            'permission' => 'required|exists:permissions,id'
        ]);

        $role->addPermission((int)$request->permission);

        return redirect()->route('tenant.account.role.show', [$domain, $role->id])->with('flash_message', 'Permission has been assigned');
    }

    public function revokePermission($domain, Role $role, Request $request)
    {
        $this->validate($request, [
            'permission' => 'required|exists:permissions,id'
        ]);

        $role->removePermission((int)$request->permission);

        return redirect()->route('tenant.account.role.show', [$domain, $role->id])->with('flash_message', 'Permission has been removed');
    }

    public function assignToAccount($domain, Role $role, Request $request)
    {
        $this->validate($request, [
            'user' => 'required|exists:users,id',
        ]);

        $role->assignToUser((int)$request->user);

        return redirect()->route('tenant.account.role.show', [$domain, $role->id])->with('flash_message', 'Role has been added to account');
    }

    public function revokeFromAccount($domain, Role $role, Request $request)
    {
        $this->validate($request, [
            'account' => 'required|exists:users,id',
        ]);

        $role->revokeFromUser((int)$request->account);

        return redirect()->route('tenant.account.role.show', [$domain, $role->id])->with('flash_message', 'Role has been removed from account');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($domain, Request $request, Role $role)
    {

        if ($role->is_default) return redirect()->route('tenant.account.role.show', [$role->id])->with('error', 'You cannot update a system default role.');

        $this->validate($request, [
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'label' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $role->update([
            'name' => $request->name,
            'label' => $request->label,
            'description' => $request->description
        ]);

        return redirect()->route('tenant.account.role.show', [$domain, $role->id])->with('flash_message', 'Role has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($domain, Role $role)
    {
        if ($role->system) return redirect()->route('tenant.account.role.show', [$role->id])->with('error', 'You cannot delete a system default role.');

        $role->delete();

        return redirect()->route('tenant.account.role.index', [$domain])->with('flash_message', 'Role has been deleted');
    }
}
