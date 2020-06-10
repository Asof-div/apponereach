<?php

namespace App\Http\Controllers\App\Tenant\Media;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

use App\Models\PlayMedia;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use File;
use Storage;
use Validator;
use ImageMaker;

class GalleryController extends Controller
{
    public function __construct(){
        
        $this->tenant = TenantManager::get();
        $this->middleware(['tenant','auth']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($domain)
    {
        $images = PlayMedia::company()->where('application', 'image')->get();

        return view('app.tenant.media-services.gallery.index', compact('images') );
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
            
            'title' =>  ['required', Rule::unique('play_media', 'title')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey())->where('application', 'image');
                }), 'min:3', 'max:200' ],
            'file' => 'required_if:file_option,upload|mimes:image/jpeg,jpg,jpeg,png|max:5000',

        ]);


        if ($validator->passes()) {

            $now = new \DateTime();
            

            if($request->ajax()){
            
                     
                $file = $request->file;
                $tmpName = $file->getFileName()."".time(); 
                $path = $this->tenant->tenant_no."/gallery/".$now->format('Y-M-d')."/";
                $filename = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $fileSize = $file->getClientSize();

                Storage::disk('local')->put("public/".$path . $tmpName."-full.".$ext,  File::get($file));


                $img = ImageMaker::make($file);

                $img->resize(120, 120);

                $img->save(storage_path("app/public/"). $path . $tmpName."-thumbnail.".$ext);

                
                $PlayMedia = PlayMedia::create([
                    'tenant_id' => $this->tenant->id,
                    'code' => $this->tenant->code,
                    'original_name' => $filename,
                    'application' => 'image',
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
            
                return response()->json(['success'=>'Image Successfully Saved.', 'images' => PlayMedia::where('application', 'image')->get()]  );

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
    public function delete($domain, Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'gallery_id' => 'required|exists:play_media,id',

        ]);


        if ($validator->passes()) {

            if($request->ajax()){
            
                $image = PlayMedia::find($request->gallery_id);

                if($image){

                    $image->delete();
                    return response()->json(['success'=>'Image Successfully Deleted.', 'images' => PlayMedia::where('application', 'image')->get()]  );

                }

                return response()->json(['error'=>['Unable to delete image.'], 'images' => PlayMedia::where('application', 'image')->get()] , 422);

            }

        }

        return response()->json(['error'=>$validator->errors()->all()], 422);
        
    }
}
