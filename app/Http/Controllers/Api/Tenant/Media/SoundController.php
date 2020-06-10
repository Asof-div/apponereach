<?php

namespace App\Http\Controllers\Api\Tenant\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\GetUserResourceTrait;
use Illuminate\Validation\Rule;
use App\Models\PlayMedia;
use App\Services\Response\ApiResponse;

use App\Http\Resources\PlayMediaResource;
use App\Http\Resources\PlayMediaCollection;

use File;
use Storage;
use Validator;

class SoundController extends Controller
{
    

 	/**
     * @OA\Get(
     *      path="/api/app/media/sounds",
     *      operationId="getAllSounds",
     *      tags={"Media Sound"},
     *      summary="Get All Sounds",
     *      description="Returns sounds data",
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
    public function index(Request $request)
    {
        $sounds =  new PlaymediaCollection(
        	PlayMedia::company($request->user()->tenant_id)->where('application', 'file')->get());

        return response()->json($sounds, 200);
    }


 	/**
     * @OA\Post(
     *      path="/api/app/media/sounds/upload",
     *      operationId="createSoundsByUpload",
     *      tags={"Media Sound"},
     *      summary="Create new sound",
     *      description="Returns sound data",
     *      @OA\Parameter(
     *          name="file",
     *          description="file upload",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="file"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successfully created"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *       @OA\Response(response=403, description="Can not Perform Action")
     *     )
     *
     */
    public function upload(Request $request)
    {

        $validator = Validator::make($request->all(), [
            
            'file' => 'required|max:5500',

        ]);

        if ($validator->fails()) {
	        return response()->json( (new ApiResponse())->error($validator->errors()->all()), 422);
	    }
        
	    try{
	    	$now = new \DateTime;
	        $tenant = $request->user()->tenant;  
	        
            set_time_limit(120);

            $file = $request->file;
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $tmpName = substr(time(), 5) . preg_replace('/[^A-Za-z0-9\-]/', '', $filename); 
            $path = $tenant->tenant_no."/sound/".$now->format('Y-M-d')."/";
            $filename = $file->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();
            $mime = $file->getClientMimeType();
            $type = $this->getResourceType($mime, $ext);
            $fileSize = $file->getClientSize();

            if ( !$this->validMime($ext) ) {
		        return response()->json( (new ApiResponse())->error(['File type must be mp3 or wav']), 422);
		    }

            Storage::disk('local')->put("public/".$path . $tmpName.".".$ext,  File::get($file));

            if(strtolower($ext) == 'mp3'){

                shell_exec('chmod -f 777 '. public_path('storage/' .$path.$tmpName.".mp3"));
                exec("sox " .public_path('storage/'.$path.$tmpName.".".$ext) ." -c 1 -r 8000 ". public_path('storage/'.$path.$tmpName.".wav"));
                exec('rm -r ' .public_path('storage/' .$path.$tmpName.".".$ext));
                shell_exec('chmod -f 777 '. public_path('storage/' .$path.$tmpName.".wav"));
                $ext = "wav"; 
            }
            
            PlayMedia::create([
                'tenant_id' => $tenant->id,
                'code' => $tenant->code,
                'original_name' => $filename,
                'title' => $filename,
                'ext' => $ext,
                'filename' => $tmpName,
                'size' => $fileSize,
                'mime_type' => $mime,
                'category' => $type,
                'error' => $file->getError()?1:0,
                'path' => $path.$tmpName.".".$ext,
                'source' => "upload",
                'exist' => true,
                'application' => 'file',
            ]);
            

			$sounds =  new PlaymediaCollection(
	        	PlayMedia::company($request->user()->tenant_id)->where('application', 'file')->get());
	        return response()->json($sounds, 200);
	    }catch(\Exception $e){
	    	\Log::log('info', $e);
            return response()->json( (new ApiResponse())->error(['Unable to complete the action.']), 403);
	    }
    }



 	/**
     * @OA\Post(
     *      path="/api/app/media/sounds/call-to-record",
     *      operationId="createSoundsWithCallToRecord",
     *      tags={"Media Sound"},
     *      summary="Create Call To Record sound",
     *      description="Returns sound data",
     *      @OA\Parameter(
     *          name="title",
     *          description="title ",
     *          required=false,
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
     *       @OA\Response(response=403, description="Can not Perform Action")
     *     )
     *
     */
    public function callToRecord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'title' => 'required',

        ]);

        if ($validator->fails()) {
	        return response()->json( (new ApiResponse())->error($validator->errors()), 422);
	    }
        
	    try{

	        $tenant = $request->user()->tenant;  

            PlayMedia::create([
                'tenant_id' => $tenant->id,
                'code' => $tenant->code,
                'title' => $request->title,
                'original_name' => $request->title,
                'voice_code' => $this->generate(),
                'source' => 'record',
                'application' => 'file',

                ]);

			$sounds =  new PlaymediaCollection(
        	PlayMedia::company($request->user()->tenant_id)->where('application', 'file')->get());

	        return response()->json($sounds, 200);
	    }catch(\Exception $e){
            return response()->json( (new ApiResponse())->error(['Unable to complete the action.']), 403);
	    }
    }

    private function getResourceType($mime, $ext)
    {
        if (starts_with($mime, 'audio')) {
            return 'audio';
        } else if (starts_with($mime, 'video')) {
            return 'video';
        } else if (starts_with($mime, 'image')) {
            return 'image';
        } else {
            return $this->getResourceTypeByExtension($ext);
        }
    }

    private function getResourceTypeByExtension($ext)
    {
        if (in_array($ext, ['mp3'])) {
            return 'audio';
        } else {
            return null;
        }
    }

    private function validMime($ext){
        if(strtolower($ext) == 'mp3' || strtolower($ext) == 'wav'){
            return true;
        }
        return false;
    }

   
 	/**
     * @OA\Delete(
     *      path="/api/app/media/sound/{id}",
     *      operationId="deleteSound",
     *      tags={"Media Sound"},
     *      summary="Delete Sound",
     *      description="Returns sound data",
     *      @OA\Parameter(
     *          name="id",
     *          description="sound id ",
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
     *       @OA\Response(response=403, description="Can not Perform Action")
     *     )
     *
     */
    public function delete($id)
    {

        $sound = PlayMedia::find($id);

		if (!$sound){
	        return response()->json( (new ApiResponse())->error('Sound is already deleted'), 403);
	    }	           	 

        if(!$sound->deletable() ){
            return response()->json( (new ApiResponse())->error('Can not delete sound file that is in use.'), 403);
        }

        if($sound->path){

            exec('rm -r ' .public_path('storage/' .$sound->path));
        }
        $sound->delete();

		$sounds =  new PlaymediaCollection(
        	PlayMedia::company($request->user()->tenant_id)->where('application', 'file')->get());
		
        return response()->json($sounds, 200);    

    }

    public function generate(){

        $collects = PlayMedia::where('tenant_id', request()->user()->tenant_id)->get();

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
