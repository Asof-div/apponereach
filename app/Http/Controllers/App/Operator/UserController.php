<?php

namespace App\Http\Controllers\App\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Operator;

use App\Models\OperatorRole as Role;
use Auth;
use Validator;

class UserController extends Controller
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
        $operators = Operator::where('sadmin', 0)->orderBy('lastname')->get();
        $roles = Role::get();

        return view('app.operator.user.index', compact('operators', 'roles') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get();
        
        return view('app.operator.user.create', compact('roles'));
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
            'firstname' => 'required|string|min:3|max:250',
            'lastname' => 'required|string|min:3|max:250',
            'email' => 'required|email|min:3|max:250|unique:operators,email',
            'job_title' => 'required|string|min:3|max:250',
            'password' => 'required|string|min:6|confirmed|max:100',

            ]);

        if($validator->passes()){

            if($request->ajax()){
                $data = $request->all();
                $user = Operator::create([
                    'lastname' => $data['lastname'],
                    'firstname' => $data['firstname'],
                    'email' => $data['email'],
                    'password' => bcrypt( $data['password'] ),
                    'job_title' => $data['job_title'],
                    ]);

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
    public function edit(Request $request, $id)
    {
        $user = Operator::find($id);

        $roles = Role::get();   
        return view('app.operator.user.edit', compact('user', 'roles'));     
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:operators,id',
            'firstname' => 'required|string|min:3|max:250',
            'lastname' => 'required|string|min:3|max:250',
            'email' => 'required|email|min:3|max:250|unique:operators,email,'.$request->user_id,
            'job_title' => 'required|string|min:3|max:250',

            ]);
        $user = Operator::find($request->user_id);
        if($user && $user->sadmin){

            return response()->json(['error'=> ['This account cannot be edited']], 422);
        }elseif ($user) {
            $data = $request->all();    
            $user->update([
                'lastname' => $data['lastname'],
                'firstname' => $data['firstname'],
                'email' => $data['email'],
                'job_title' => $data['job_title'],

                ]);
            $user->roles()->detach();

            if($request->has('roles')){
                foreach ($request->roles as $roleId) {
                    $user->addRole((int) $roleId);
                    
                }
            }

            return response()->json(['success'=>'User Account Successfully Updated.', 'user' => $user ]);
        }

        return response()->json(['error'=>$validator->errors()->all()], 422);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
                'user_id' => 'required|exists:operators,id',
            ]);
        $user = Operator::find($request->user_id);
        if($user && $user->sadmin){

            return redirect()->route('operator.user.index')->withErrors(['This account cannot be deleted']);
        }elseif ($user) {
            $user->delete();

            return redirect()->route('operator.user.index')->with('flash_message', 'User successfully deleted');

        }

        return redirect()->route('operator.user.index')->withErrors(['User not found']);

    }
}
