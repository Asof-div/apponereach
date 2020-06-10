<?php

namespace App\Http\Controllers\Api\Tenant\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Number;
use App\Traits\GetUserResourceTrait;
use App\Repos\NumberRepo;
use App\Services\Response\ApiResponse;
use Validator;
use Illuminate\Validation\Rule;


class NumberController extends Controller
{
    use GetUserResourceTrait;
    /**
     * @OA\Get(
     *      path="/api/app/media/numbers",
     *      operationId="getNumbers",
     *      tags={"Media Destination Numbers"},
     *      summary="Get Numbers",
     *      description="Returns Destination Number List",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     */
    public function index(Request $request){

    	$tenant = $request->user()->tenant;

        $numbers = Number::company($tenant->id)->with(['user'])->get();
     
        return response()->json(['numbers' => $numbers, 'user' => $this->getUser()], 200);
    }



    public function store(Request $request, NumberRepo $numberRepo)
    {
        $validator = Validator::make($request->all(), [

            'user_id' => 'required|exists:users,id',
            'number' => ['required','phone_number', 'digits:11', 'numeric', Rule::unique('numbers')->where(function ($query) {
                return $query->where('tenant_id', request()->user()->tenant_id);
                }) ],
        ]);

        $tenant = $request->user()->tenant;

        if ($validator->fails()) {
            return response()->json( (new ApiResponse())->error($validator->errors()), 422);
        }
    
        $numberRepo->store($request->user(), $request->all());    

        $numbers = Number::company($tenant->id)->with(['user'])->get();

        return response()->json(['numbers' => $numbers, 'user' => $this->getUser()], 200);
    }


    public function update($id, Request $request, NumberRepo $numberRepo)
    {
        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'user_id' => 'required|exists:users,id',
            'number' => ['required','phone_number', 'digits:11', 'numeric', Rule::unique('numbers')->where(function ($query) {
                return $query->where('tenant_id', request()->user()->tenant_id);
                })->ignore($id) ],
        ]);

        $tenant = $request->user()->tenant;

        if ($validator->fails()) {
            return response()->json( (new ApiResponse())->error($validator->errors()), 422);
        }
        $number = Number::find($id);
        
        if (!$number) {
            return response()->json( (new ApiResponse())->error('Number is already deleted'), 403);
        }
 
        $destination = null;
        if(!$numberRepo->deletable($number, $destination)){
            return response()->json( (new ApiResponse)->error('Can not edit number used as destination in '. $destination) , 403);
        }
        
        $numberRepo->update($request->user(), $number, $request->all());


        $numbers = Number::company($tenant->id)->with(['user'])->get();

        return response()->json(['numbers' => $numbers, 'user' => $this->getUser()], 200);
    }


    public function changeStatus($id, Request $request){

        $number = Number::find($id);
        $tenant = $request->user()->tenant;

        if (!$number) {
            return response()->json( (new ApiResponse())->error(['Number is already deleted']), 403);
        }

        $number->update([
            'status' => $request->status ? true : false,
        ]);

        $numbers = Number::company($tenant->id)->with(['user'])->get();

        return response()->json(['numbers' => $numbers, 'user' => $this->getUser()], 200);
    }


    public function remove($id, Request $request, NumberRepo $numberRepo){

        $number = Number::find($id);
        $tenant = $request->user()->tenant;

        if (!$number) {
            return response()->json( (new ApiResponse())->error(['Number is already deleted']), 403);
        }

        $destination = null;
        if(!$numberRepo->deletable($number, $destination)){
            return response()->json( (new ApiResponse)->error('Can not remove number used as destination in '. $destination) , 403);
        }
        
        try{

            $number->update([
                'status' => false,
            ]);

            $numbers = Number::company($tenant->id)->with(['user'])->get();

            return response()->json(['numbers' => $numbers, 'user' => $this->getUser()], 200);
    
        }catch(\Exception $e){

            return response()->json( (new ApiResponse)->error('Unable to remove number from usable list') , 403);
        }
    }

    

    public function delete($id, Request $request, NumberRepo $numberRepo){
        
        $number = Number::company($request->user()->tenant_id)->where('id', $id)->first();
        $tenant = $request->user()->tenant;


        if(!$number){ 
            return response()->json( (new ApiResponse)->error('Number have already been deleted') , 403);
        }
        $destination = null;
        if(!$numberRepo->deletable($number, $destination)){
            return response()->json( (new ApiResponse)->error('Can not delete number used as destination in '. $destination) , 403);
        }
        
        try{

            $numberRepo->delete($number);

            $numbers = Number::company($tenant->id)->with(['user'])->get();

            return response()->json(['numbers' => $numbers, 'user' => $this->getUser()], 200);
    
        }catch(\Exception $e){

            return response()->json( (new ApiResponse)->error('Unable to delete number') , 403);
        }
    
           
    }

}
