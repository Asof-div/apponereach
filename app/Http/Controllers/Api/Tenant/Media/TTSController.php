<?php

namespace App\Http\Controllers\Api\Tenant\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\GetUserResourceTrait;
use Validator;
use Illuminate\Validation\Rule;
use App\Models\PlayMedia;
use App\Services\Response\ApiResponse;

class TTSController extends Controller
{
    use GetUserResourceTrait;
    

 	/**
     * @OA\Get(
     *      path="/api/app/media/tts",
     *      operationId="listTextToSpeech",
     *      tags={"Media Text-To-Speech"},
     *      summary="List Text To Speech",
     *      description="Returns text to speech data",
     *      @OA\Response(
     *          response=200,
     *          description="successfully "
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *       @OA\Response(response=402, description="Can not Perform Action")
     *     )
     *
     */
    public function index(Request $request)
    {
        $txttosp = PlayMedia::company($request->user()->tenant_id)->where('application', 'tts')->get();

        return response()->json($txttosp, 200);
    }



 	/**
     * @OA\Post(
     *      path="/api/app/media/tts",
     *      operationId="createTextToSpeech",
     *      tags={"Media Text-To-Speech"},
     *      summary="Create Text To Speech",
     *      description="Returns text to speech data",
     *      @OA\Parameter(
     *          name="title",
     *          description="Text title ",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="text",
     *          description="text content",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successfully created"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *       @OA\Response(response=402, description="Can not Perform Action")
     *     )
     *
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'title' => ['required', Rule::unique('play_media', 'title')->where(function ($query) {
                return $query->where('tenant_id', request()->user()->tenant_id)->where('application', 'tts');
                }), 'min:3', 'max:200' ],
            'text' => 'required|min:3|max:5500',

        ]);


        if ($validator->fails()) {
	        return response()->json( (new ApiResponse())->error($validator->errors()), 422);
	    }
        $tenant = $request->user()->tenant;  

        PlayMedia::create([
            'tenant_id' => $tenant->id,
            'code' => $tenant->code,
            'voice_code' => '',
            'application' => 'tts',
            'category' => 'tts',
            'original_name' => $request->title,
            'mime_type' => 'greeting',
            'title' => $request->title,
            'error' => 0,
            'content' => $request->text,
            'source' => "tts",
            'exist' => 1,
        ]);

        $txttosp = PlayMedia::company($request->user()->tenant_id)->where('application', 'tts')->get();

        return response()->json($txttosp, 200);
    }



 	/**
     * @OA\PUT(
     *      path="/api/app/media/tts/{id}",
     *      operationId="updateTextToSpeech",
     *      tags={"Media Text-To-Speech"},
     *      summary="Create new Extension",
     *      description="Returns extension data",
     *      @OA\Parameter(
     *          name="id",
     *          description="tts unique id ",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="title",
     *          description="title to describe the content",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="text",
     *          description="text content",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successfully updated"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *       @OA\Response(response=402, description="Can not Perform Action")
     *     )
     *
     */
    public function update($id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => ['required', Rule::unique('play_media', 'title')->where(function ($query) {
                return $query->where('tenant_id', request()->user()->tenant_id)->where('application', 'tts');
                })->ignore($request->tts_id), 'min:3', 'max:200' ],
            'text' => 'required|min:3|max:5500',
        ]);


        if ($validator->fails()) {
	        return response()->json( (new ApiResponse())->error($validator->errors()), 422);
	    }

        $tts = PlayMedia::find($id);

	    if (!$tts){

	        return response()->json( (new ApiResponse())->error('Text is already deleted'), 402);
	    }	           	 

        $tts->update([
            'original_name' => $request->title,
            'title' => $request->title,
            'error' => 0,
            'content' => $request->text,
            
        ]);

        $txttosp = PlayMedia::company($request->user()->tenant_id)->where('application', 'tts')->get();
        
        return response()->json($txttosp, 200);


    }



 	/**
     * @OA\DELETE(
     *      path="/api/app/media/tts/{id}",
     *      operationId="deleteTextToSpeech",
     *      tags={"Media Text-To-Speech"},
     *      summary="Delete TextToSpeech",
     *      description="Returns tts data",
     *      @OA\Parameter(
     *          name="id",
     *          description="text id ",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successfully deleted"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *       @OA\Response(response=402, description="Can not Perform Action")
     *     )
     *
     */
    public function delete($id)
    {
        
        $tts = PlayMedia::find($id);
		
		if (!$tts){
	        return response()->json( (new ApiResponse())->error('Text is already deleted'), 402);
	    }	           	 

        if(!$tts->deletable() ){
            return response()->json( (new ApiResponse())->error('Text is already deleted'), 402);
        }

        $tts->delete();

        $txttosp = PlayMedia::company(request()->user()->tenant_id)->where('application', 'tts')->get();

        return response()->json($txttosp, 200);       
    }


    public function generate(){

        $collects = PlayMedia::where('tenant_id', request()->user()->tenant->id)->get();

        $digit = sprintf("%4d", rand(1, 9999));
        $result = $collects->filter(function ($item, $key) use($digit) {
            return $item->voice_code == $digit;
        });
        while ($result->count() > 0) {
            
            $digit = sprintf("%4d", rand(1, 9999));
            $result = $collects->filter(function ($item, $key) use($digit) {
                return $item->voice_code == $digit;
            });
            
        }

        return $digit;
    }


}
