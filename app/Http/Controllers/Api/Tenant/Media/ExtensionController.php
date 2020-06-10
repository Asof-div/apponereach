<?php

namespace App\Http\Controllers\Api\Tenant\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Extension;
use App\Traits\GetUserResourceTrait;
use Validator;
use App\Repos\ExtensionRepo;
use App\Services\Response\ApiResponse;
use Illuminate\Validation\Rule;


class ExtensionController extends Controller
{
    use GetUserResourceTrait;


    /**
     * @OA\Get(
     *      path="/api/app/media/extensions",
     *      operationId="getExtension",
     *      tags={"Media Extension"},
     *      summary="Get Extension",
     *      description="Return Extension List.",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *     )
     *
     */
    public function index(Request $request){

    	$tenant = $request->user()->tenant;

        $extensions = Extension::company($tenant->id)->with(['user'])->get();

    	return response()->json(['extensions' => $extensions, 'user' => $this->getUser()], 200);
    }

 	/**
     * @OA\Post(
     *      path="/api/app/media/extensions",
     *      operationId="creteNewExtension",
     *      tags={"Media Extension"},
     *      summary="Create new Extension",
     *      description="Returns extension data",
     *      @OA\Parameter(
     *          name="name",
     *          description="Extension user display name ",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="extension",
     *          description="user extension number 4 - 5 digits and must be unique",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="user_id",
     *          description="Assigned owner of the extension",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="account successfully created"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *       @OA\Response(response=403, description="Can not Perform Action")
     *     )
     *
     */
    public function store(Request $request, ExtensionRepo $extensionRepo, ApiResponse $apiResponse){

		$validator = Validator::make($request->all(), [
            
            'user_id' => 'required',
            'name' => 'required|unique:extensions,name',
            'extension' => ['required', 'numeric', Rule::unique('call_flows', 'dial_string')->where(function ($query) {
                return $query->where('tenant_id', request()->user()->tenant_id);
                }), 'digits_between:3,5'],

        ]);

        if($validator->fails()){
	        return response()->json( (new ApiResponse)->error($validator->errors()) , 422);
        }

        $tenant = $request->user()->tenant;
                  
        $exten = $extensionRepo->store($request->all());  
        
        $extensions = Extension::company($tenant->id)->with(['user'])->get();
          
        return response()->json([ 'extension' => $exten, 'extensions' => $extensions], 200);      
    }

    /**
     * @OA\Get(
     *      path="/api/app/media/extensions/{number}",
     *      operationId="getExtensionById",
     *      tags={"Media Extension"},
     *      summary="Get Extenision Information",
     *      description="Returns extension deta",
     *      @OA\Parameter(
     *          name="number",
     *          description="Extension number ",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successfully Operattion"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *     )
     *
     */
    public function show($number)
    {
        $extension = Extension::with('call_flow')->company(request()->user()->tenant_id)->where('number', $number)->first();

        if(!$extension){

            $response = (new ApiResponse)->error('Extension does not exist');
            return response()->json($response, 403);
        }

        return response()->json(['extension' => $extension], 200);
    }

    /**
     * @OA\Put(
     *      path="/api/app/media/extensions/{id}",
     *      operationId="updateExtension",
     *      tags={"Media Extension"},
     *      summary="Update Extension",
     *      description="Returns extension data",
     *      @OA\Parameter(
     *          name="name",
     *          description="Extension user display name ",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="extension",
     *          description="user extension number 4 - 5 digits and must be unique",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="user_id",
     *          description="Assigned owner of the extension",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="change_password",
     *          description="Checkbox to generate new password",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="account successfully created"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *       @OA\Response(response=403, description="Cant Perform Action")
     *     )
     *
     */
    public function update($id, Request $request, ExtensionRepo $extensionRepo, ApiResponse $apiResponse){

        $validator = Validator::make($request->all(), [
            
            'user_id' => 'required',
            'name' => 'required|unique:extensions,name,'.$id,
            'extension' => ['required', 'numeric', Rule::unique('call_flows', 'dial_string')->where(function ($query) {
                return $query->where('tenant_id', request()->user()->tenant_id);
                })->ignore(request()->call_flow_id), 'digits_between:3,5'],

        ]);

        $exten = Extension::find($id);

        if($validator->fails()){
            return response()->json( (new ApiResponse)->error($validator->errors()) , 422);
        }

        if(!$exten){
            return response()->json( (new ApiResponse)->error('Extension have already been deleted') , 403);
        }

        $destination = null;
        if(!$extensionRepo->deletable($exten, $destination)){
            return response()->json( (new ApiResponse)->error('Can not edit extension used as destination in '. $destination) , 403);
        }

        $tenant = $request->user()->tenant;
                  
        $exten = $extensionRepo->update($exten, $request->all());  

        $extensions = Extension::company($tenant->id)->with(['user'])->get();
          
        return response()->json([ 'extension' => $exten, 'extensions' => $extensions], 200);      
    }


    /**
     * @OA\Delete(
     *      path="/api/app/media/extensions/{number}",
     *      operationId="deleteExtension",
     *      tags={"Media Extension"},
     *      summary="Delete Extension",
     *      description="Returns extension data",
     *      @OA\Parameter(
     *          name="number",
     *          description="user extension number ",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="account successfully created"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *       @OA\Response(response=403, description="Cant Perform Action")
     *     )
     *
     */
    public function delete($number, ExtensionRepo $extensionRepo)
    {
        
        $tenant = request()->user()->tenant;

        $exten = Extension::company($tenant->id)->where('number', $number)->first();

        if(!$exten){ 
            return response()->json( (new ApiResponse)->error('Extension have already been deleted') , 403);
        }
        $destination = null;
        if(!$extensionRepo->deletable($exten, $destination)){
            return response()->json( (new ApiResponse)->error('Can not delete any extension used as destination in '. $destination) , 403);
        }
        
        try{

            $extensionRepo->delete($exten);
            $extensions = Extension::company($tenant->id)->with(['user'])->get();
        
            return response()->json(['extensions' => $extensions, 'user' => $this->getUser()], 200);
        }catch(\Exception $e){

            return response()->json( (new ApiResponse)->error('Unable to delete extension') , 403);
        }

    }


}
