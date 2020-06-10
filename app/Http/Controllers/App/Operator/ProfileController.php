<?php

namespace App\Http\Controllers\App\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;
use File;
use Auth;
use Hash;

class ProfileController extends Controller
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
        

        return view('app.operator.profile.index');
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
            'type' => 'required',
            'firstname' => 'required_if:type,info|min:3|max:250',
            'lastname' => 'required_if:type,info|min:3|max:250',
            'email' => 'required_if:type,info|email|max:250|unique:operators,email,'.Auth::id(),
            'job_title' => 'required_if:type,info|max:250',
            'password' => 'required_if:type,password|confirmed|min:8|max:50',

        ]);
 
        switch ($request->type) {
            case 'info':
                    Auth::user()->update(['firstname' => $request->firstname, 'lastname' => $request->lastname, 'email' => $request->email, 'job_title' => $request->job_title]);
                break;
            
            case 'password':
                    if(!Hash::check($request->old_password, Auth::user()->password) ){

                        return redirect()->route('operator.profile.index')->withErrors(['The specified Old password does not match the database password']);

                    }

                    Auth::user()->update(['password' =>bcrypt($request->password)]);
                    return redirect()->route('operator.profile.index')->with('flash_message', 'Password Successfully Updated.');
                break;            
            default:
                # code...
                break;
        }

        return redirect()->route('operator.profile.index')->with('flash_message', 'User Profile Successfully Updated.');

    }


    function picture(Request $request){

        $this->validate($request, [

            'picture' => 'required|image|max:3000',

        ]);

                                
        if($request->hasFile('picture') ){

            $picture = $request->file('picture');

            $image_path = storage_path()."/app/public/".Auth::user()->avatar;
        
            if (File::exists($image_path)) {
                
                File::delete($image_path);
            }

            $digest = Auth::id().'/'.md5(time());
            $tmpName = 'operator/profile_picture/user_'.$digest.".". $picture->getClientOriginalExtension(); 
        
            Storage::disk('local')->put("public/". $tmpName,  File::get($picture));
            Auth::user()->update(['image' => $tmpName]);

            return redirect()->route('operator.profile.index')->with('flash_message', 'Profile picture successfully uploaded.');
        }
    
        return redirect()->route('operator.profile.index')->withErrors(['Unable to change profile picture.']);        

    }
}
