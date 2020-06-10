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


class TTSController extends Controller
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
        $txttosp = PlayMedia::company()->where('application', 'tts')->get();
        return view('app.tenant.media-services.tts.index', compact('txttosp') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('app.tenant.media-services.tts.create' );        
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
            
            'title' => ['required', Rule::unique('play_media', 'title')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey())->where('application', 'tts');
                }), 'min:3', 'max:200' ],
            'type' => 'required',
            'text' => 'required|min:3|max:5500',

        ]);


        if ($validator->passes()) {

            $now = new \DateTime();

            if($request->ajax()){

                
                $tts = PlayMedia::create([
                    'tenant_id' => $this->tenant->id,
                    'code' => $this->tenant->code,
                    'voice_code' => $this->generate(),
                    'application' => 'tts',
                    'original_name' => $request->title,
                    'mime_type' => $request->type,
                    'title' => $request->title,
                    'error' => 0,
                    'content' => $request->text,
                    'source' => "tts",
                    'exist' => 1,
                ]);

                return response()->json(['success'=>'TTS Successfully Saved.', 'txttosp' => PlayMedia::where('application', 'tts')->get() ]  );

            }

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
    public function update($domain, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'tts_id' => 'required|exists:play_media,id',
            'title' => ['required', Rule::unique('play_media', 'title')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey())->where('application', 'tts');
                })->ignore($request->tts_id), 'min:3', 'max:200' ],
            'type' => 'required',
            'text' => 'required|min:3|max:5500',

        ]);


        if ($validator->passes()) {

            $now = new \DateTime();
            
            

            if($request->ajax()){

                $tts = PlayMedia::find($request->tts_id);

                $tts->update([
                    'original_name' => $request->title,
                    'mime_type' => $request->type,
                    'title' => $request->title,
                    'error' => 0,
                    'content' => $request->text,
                    
                ]);

                return response()->json(['success'=>'TTS Successfully Updated.', 'txttosp' => PlayMedia::where('application', 'tts')->get() ]  );

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
            
            'tts_id' => 'required|exists:play_media,id', 
        
        ]);


        if ($validator->passes()) {

            $now = new \DateTime();
            
            

            if($request->ajax()){

                $tts = PlayMedia::find($request->tts_id);
                if(!$tts->deletable() ){


                    return response()->json(['error'=>['Unable to delete tts. Ensure it is not use.' ] ], 422);
                }

                $tts->delete();


                return response()->json(['success'=>'TTS Successfully Deleted.', 'txttosp' => PlayMedia::where('application', 'tts')->get() ]  );

            }

        }


        return response()->json(['error'=>$validator->errors()->all()], 422);        
       

       
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
