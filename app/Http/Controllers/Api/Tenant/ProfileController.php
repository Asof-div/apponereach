<?php

namespace App\Http\Controllers\Api\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Call;
use Validator;
use Auth;
use Hash;
use Storage;
use File;
use App\Services\Response\ApiResponse;
use App\Http\Resources\UserResource;
use Illuminate\Validation\Rule;
use App\Http\Resources\CallResource;

class ProfileController extends Controller
{

    public function __construct(){

        $this->middleware('auth:api');
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [

            'firstname' => 'required|min:3',
            'lastname' => 'required|min:3',
            'email' => ['required', 'email', Rule::unique('users', 'email')->where(function ($query) {
                return $query->where('tenant_id', request()->user()->tenant_id);
                })->ignore(request()->user()->id)],
            
        ]);

        if ($validator->fails()) {
            return response()->json( (new ApiResponse())->error($validator->errors()), 422);
        }

        $tenant = $user->tenant;

        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->save();

        return response()->json(['data' =>  new UserResource($user) ], 200);

    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [

            'old_password' => 'required|min:6',
            'password' => 'required|confirmed|min:6',

        ]);



        if ($validator->fails()) {
            return response()->json( (new ApiResponse())->error($validator->errors()), 422);
        }

        $tenant = $user->tenant;
        
        if(!Hash::check($tenant->tenant_no.$request->old_password, Auth::user()->password) ){

            return response()->json( (new ApiResponse())->error(['old_password' => ['The specified Old password does not match the database password']]), 422);
        }

        $user->password = Hash::make($tenant->tenant_no.$request->password);
        $user->save();

        return response()->json(['data' =>  new UserResource($user) ], 200);
    }

   
    public function history(Request $request){
        $user = $request->user();
        $calls = Call::whereHas('users',function($query) use ($user){
            $query->where('id', $user->id);
        })->get();

        $last_call_time = $calls->count() > 0 ? $calls->last()->start_time->format('Y-m-d H:i:s') : null;
    
        return response()->json(CallResource::collection($calls)->additional(['meta' => [
                'last_call_time' => $last_call_time,
            ]]), 200);
    }


    function picture(Request $request){

        $validator = Validator::make($request->all(), [

            'picture' => 'required|image|max:3000',

        ]);


        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 422);
        }
   
            
        if($request->hasFile('picture') ){

            $picture = $request->file('picture');
            $tenant = $request->user()->tenant;

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
