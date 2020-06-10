<?php

namespace App\Http\Controllers\App\Tenant\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Role;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;

class UserController extends Controller
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
        $users = User::company()->orderBy('lastname')->get();

        return view('app.tenant.account.user.index', compact('users'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::company()->orWhere(function($query){
            $query->where('tenant_id', null)->where('system', true);
        })->get();   
        return view('app.tenant.account.user.create', compact('roles'));
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
            
            'firstname' => 'required|string|min:3|max:255',

            'lastname' => 'required|string|min:3|max:255',

            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                }) ],
            'job_title' => 'required|string|min:3|max:255',
            'password' => 'required|string|min:6|confirmed',

        ]);

        $tenant = TenantManager::get();

        $salt = $tenant->tenant_no;

        if ($validator->passes()) {

            if($request->ajax()){
            
                $data = $request->all();
            
                $user = User::create([
                    'lastname' => ucfirst($data['lastname']),
                    'firstname' => ucfirst($data['firstname']),
                    'tenant_id' => $tenant->id,
                    'email' => strtolower($data['email']),
                    'manager' => isset($data['manager']) ? 1 : 0,
                    'password' => bcrypt($salt.$data['password'] ),
                ]);

                $user->profile->update(['role' => $data['job_title']]);

                if($request->has('roles')){
                    foreach ($request->roles as $roleId) {
                        $user->addRole((int) $roleId);
                        
                    }
                }
                return response()->json(['success'=>'User Account Successfully Created.', 'user' => $user ]);
                
            }

        }


        return response()->json(['error'=>$validator->errors()->all()], 422);


        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain, Request $request, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($domain, Request $request, $id)
    {
        $user = User::company()->find($id);

        $roles = Role::company()->orWhere(function($query){
            $query->where('tenant_id', null)->where('system', true);
        })->get();   
        return view('app.tenant.account.user.edit', compact('user', 'roles'));        
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
            
            'firstname' => 'required|string|min:3|max:255',

            'lastname' => 'required|string|min:3|max:255',

            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })->ignore($request->user_id) ],
            'job_title' => 'required|string|min:3|max:255'

        ]);

        $tenant = TenantManager::get();

        $this->salt = $tenant->tenant_no;

        if ($validator->passes()) {

            if($request->ajax()){
            
                $data = $request->all();
            
                $user = User::find($request->user_id);
                $user->update([
                    'lastname' => ucfirst($data['lastname']),
                    'firstname' => ucfirst($data['firstname']),
                    'tenant_id' => $tenant->id,
                    'email' => strtolower($data['email']),
                ]);
                $user->profile->update(['role' => $data['job_title']]);

                $user->roles()->detach();

                if($request->has('roles')){
                    foreach ($request->roles as $roleId) {
                        $user->addRole((int) $roleId);
                        
                    }
                }
                return response()->json(['success'=>'User Account Successfully Updated.', 'user' => $user ]);
                
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
    public function delete(Request $request, $domain)
    {
        //
    }
}
