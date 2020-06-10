<?php

namespace App\Http\Controllers\Api\Tenant\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

use App\Traits\GetUserResourceTrait;
use App\Services\Response\ApiResponse;
use App\Models\PlayMedia;

use App\Http\Resources\PlayMediaResource;
use App\Http\Resources\PlayMediaCollection;

use File;
use Storage;
use Validator;
use ImageMaker;

class GalleryController extends Controller
{
    use GetUserResourceTrait;
    

 	/**
     * @OA\Get(
     *      path="/api/app/media/images",
     *      operationId="getAllImages",
     *      tags={"Media Image"},
     *      summary="Get All Images",
     *      description="Returns images data",
     *      @OA\Response(
     *          response=200,
     *          description="account successfully created"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *       @OA\Response(response=402, description="Can not Perform Action")
     *     )
     *
     */
    public function index(Request $request)
    {
        $images =  new PlaymediaCollection(
            PlayMedia::company($request->user()->tenant_id)->where('application', 'image')->get());
        return response()->json($images, 200);
    }


 	/**
     * @OA\Post(
     *      path="/api/app/media/images",
     *      operationId="createImage",
     *      tags={"Media Image"},
     *      summary="Create Image",
     *      description="Returns Image data",
     *      @OA\Parameter(
     *          name="file",
     *          description="Upload image file ",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description=" successfully created"
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
            
            'file' => 'required|mimes:image/jpeg,jpg,jpeg,png|max:5000',

        ]);


        if ($validator->fails()) {
	        return response()->json( (new ApiResponse())->error($validator->errors()), 422);
        }

        try{
	        $tenant = $request->user()->tenant;  
            $now = new \DateTime;
                     
            $file = $request->file;
            $tmpName = $file->getFileName()."".time(); 
            $path = $tenant->tenant_no."/gallery/".$now->format('Y-M-d')."/";
            $filename = $file->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();
            $fileSize = $file->getClientSize();

            Storage::disk('local')->put("public/".$path . $tmpName."-full.".$ext,  File::get($file));


            $img = ImageMaker::make($file);

            $img->resize(120, 120);

            $img->save(storage_path("app/public/"). $path . $tmpName."-thumbnail.".$ext);

            
            PlayMedia::create([
                'tenant_id' => $tenant->id,
                'code' => $tenant->code,
                'original_name' => $filename,
                'application' => 'image',
                'category' =>'image',
                'title' => $filename,
                'ext' => $ext,
                'filename' => $tmpName,
                'size' => $fileSize,
                'mime_type' => $file->getMimeType(),
                'error' => $file->getError()?1:0,
                'path' => $path.$tmpName."-full.".$ext,
                'source' => $path.$tmpName."-thumbnail.".$ext,
                'exist' => 1,
            ]);
        
            $images =  new PlaymediaCollection(
                    PlayMedia::company($request->user()->tenant_id)->where('application', 'image')->get());
            return response()->json($images, 200);
            
        }catch(\Exception $e){

	        return response()->json( (new ApiResponse())->error('Unable to upload image'), 402);
        }

    }



 	/**
     * @OA\Delete(
     *      path="/api/app/media/images/{id}",
     *      operationId="deleteImage",
     *      tags={"Media Image"},
     *      summary="Delete From Image",
     *      description="Returns image data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Image id ",
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
            
        $image = PlayMedia::find($id);

        if(!$image){
	        return response()->json( (new ApiResponse())->error('Image is already deleted'), 402);
	    }

        $image->delete();

        $images =  new PlaymediaCollection(
            PlayMedia::company($request->user()->tenant_id)->where('application', 'image')->get());
        return response()->json($images, 200);
             
    }
}
