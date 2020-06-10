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


class SoundController extends Controller
{
    
    public function __construct(){
        
        $this->tenant =  TenantManager::get();
        $this->middleware(['tenant','auth']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($domain)
    {
        $sounds = PlayMedia::company()->where('application', 'file')->get();
        return view('app.tenant.media-services.sound.index', compact('sounds') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('app.tenant.media-services.sound.create' );        
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
            
            'title' => 'required_if:file_option,record',
            'file_option' => 'required',
            'file' => 'required_if:file_option,upload|max:5500',

        ]);

        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all()], 422);
            

        if($request->ajax()){
    
            $now = new \DateTime();

            if($request->file_option == 'upload'){
            
                set_time_limit(120);

                $file = $request->file;
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $tmpName = substr(time(), 5) . preg_replace('/[^A-Za-z0-9\-]/', '', $filename); 
                $path = $this->tenant->tenant_no."/sound/".$now->format('Y-M-d')."/";
                $filename = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $mime = $file->getClientMimeType();
                $type = $this->getResourceType($mime, $ext);
                $fileSize = $file->getClientSize();

                if ( !$this->validMime($ext) ) return response()->json(['error' => 'File type must be mp3 or wav'], 422);

                Storage::disk('local')->put("public/".$path . $tmpName.".".$ext,  File::get($file));

                if(strtolower($ext) == 'mp3'){

                    shell_exec('chmod -f 777 '. public_path('storage/' .$path.$tmpName.".mp3"));
                    exec("sox " .public_path('storage/'.$path.$tmpName.".".$ext) ." -c 1 -r 8000 ". public_path('storage/'.$path.$tmpName.".wav"));
                    exec('rm -r ' .public_path('storage/' .$path.$tmpName.".".$ext));
                    shell_exec('chmod -f 777 '. public_path('storage/' .$path.$tmpName.".wav"));
                    $ext = "wav"; 
                }
                
                $sound = PlayMedia::create([
                    'tenant_id' => $this->tenant->id,
                    'code' => $this->tenant->code,
                    'voice_code' => $this->generate(),
                    'original_name' => $filename,
                    'title' => $filename,
                    'ext' => $ext,
                    'filename' => $tmpName,
                    'size' => $fileSize,
                    'mime_type' => $mime,
                    'category' => $type,
                    'error' => $file->getError()?1:0,
                    'path' => $path.$tmpName.".".$ext,
                    'source' => "Upload",
                    'exist' => 1,
                ]);
                

            
            }else{
                $sound = PlayMedia::create([
                    'tenant_id' => $this->tenant->id,
                    'code' => $this->tenant->code,
                    'title' => $request->title,
                    'original_name' => $request->title,
                    'voice_code' => $this->generate(),
                    'source' => 'record',

                    ]);
            }

            return response()->json(['success'=>'Sound Successfully Saved.']  , 200);

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list($domain)
    {
        $sounds = PlayMedia::get();
        return view('app.tenant.sound.partials.table', compact('sounds') );
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
    public function delete($domain, Request $request)
    {

        $validator = Validator::make($request->all(), [
            
            'sound_id' => 'required|exists:play_media,id', 
        
        ]);


        if ($validator->passes()) {

            if($request->ajax()){

                 $tenant = TenantManager::get();

                $sound = PlayMedia::find($request->sound_id);
                if(!$sound || !$sound->deletable() ){

                    return response()->json(['error'=>['Unable to delete sound. Ensure it is not use.'] ], 422);
                }

                if($sound->path){

                    exec('rm -r ' .public_path('storage/' .$sound->path));
                }
                $sound->delete();

                return response()->json(['success'=>'Sound Successfully Deleted.', 'sounds' => PlayMedia::get() ], 200  );

            }


        }


        return response()->json(['error'=>$validator->errors()->all()]);

    }

    public function generate(){

        $collects = PlayMedia::where('tenant_id', $this->tenant->id)->get();

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
