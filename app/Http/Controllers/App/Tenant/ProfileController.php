<?php

namespace App\Http\Controllers\App\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use Validator;
use Auth;
use Hash;
use Storage;
use File;

class ProfileController extends Controller
{

    public function __construct(){

        $this->middleware('auth:web');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        

        return view('app.tenant.profile.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

            'firstname' => 'required_if:type,name|min:3',
            'lastname' => 'required_if:type,name|min:3',

            'email' => 'required_if:type,email|email',

            'role' => 'required_if:type,role',

            'gender' => 'required_if:type,gender',

            'password' => 'required_if:type,password|confirmed|min:8',

        ]);



        if ($validator->passes()) {

            if($request->ajax()){
                $tenant = Auth::user()->tenant;
                switch ($request->type) {
                    case 'name':
                            Auth::user()->update(['firstname' =>$request->firstname, 'lastname' => $request->lastname]);
                        break;
                    case 'email':
                            Auth::user()->update(['email' =>$request->email]);
                        break;
                    case 'password':
                            if(!Hash::check($tenant->tenant_no.$request->old_password, Auth::user()->password) ){

                                return response()->json(['error'=> ['The specified Old password does not match the database password']]);
                            }

                            Auth::user()->update(['password' =>bcrypt($tenant->tenant_no.$request->password), 'active' => 1]);
                        break;
                    case 'role':
                            Auth::user()->profile->update(['role' =>$request->role]);
                        break;
                    case 'gender':
                            Auth::user()->profile->update(['gender' =>$request->gender]);
                        break;
                    
                    default:
                        # code...
                        break;
                }

            }



            return response()->json(['success'=>'User Profile Successfully Updated.', 'user' => Auth::user(), 'profile' => Auth::user()->profile ]);

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


    function picture(Request $request){

        $validator = Validator::make($request->all(), [

            'picture' => 'required|image|max:3000',

        ]);


        if ($validator->passes()) {

            if($request->ajax()){
            
            
                    
                if($request->hasFile('picture') ){

                    $picture = $request->file('picture');
                    $tenant = TenantManager::get();
        
                    $image_path = storage_path()."/app/public/".Auth::user()->profile->avatar;
                
                    if (File::exists($image_path)) {
                        
                        File::delete($image_path);
                        // unlink($image_path);
                    }

                    $digest = Auth::id().'/'.md5(time());
                    $tmpName = 'tenant_'.$tenant->id.'/profile_picture/user_'.$digest.".". $picture->getClientOriginalExtension(); 
                
                    Storage::disk('local')->put("public/". $tmpName,  File::get($picture));
                    Auth::user()->profile->update(['avatar' => $tmpName]);

                }
            

            return response()->json(['success'=>'Profile picture successfully uploaded.']);

            }
        }


        return response()->json(['error'=>$validator->errors()->all()], 422);

        

    }
}
