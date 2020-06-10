<?php

namespace App\Http\Controllers\App\Tenant\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Extension;
use App\Models\User;
use App\Models\Number;  
use App\Models\PilotLine;  
use App\Models\DeviceBrand;

use App\Repos\ExtensionRepo;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use Illuminate\Validation\Rule;
use Validator;


class ExtensionController extends Controller
{

    public function __construct(){

        $this->middleware(['tenant', 'auth']);
        $this->extensionRepo = new ExtensionRepo();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $extens = Extension::company()->where('type', 'sip_profile')->get();   
        return view('app.tenant.media-services.exten.index', compact('extens') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $extens = Extension::company()->where('type', 'sip_profile')->get();   
        $users = User::company()->get();
        $pilot_lines = PilotLine::company()->get();
        return view('app.tenant.media-services.exten.create', compact('users', 'extens', 'pilot_lines'));
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
            
            'user_id' => 'required',
            // 'extension' => 'required|unique:call_flows,exten|min:4|max:5',
            'extension' => ['required', 'numeric', Rule::unique('call_flows', 'dial_string')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                }), 'min:1000', 'max:99999' ],
            'voicemail_pin' => 'required_if:voicemail,1',
            'full_monitoring_pin' => 'required_if:full_monitoring,1',
            'whispering_pin' => 'required_if:whispering,1',
            'eavesdropping' => 'required_if:eavesdropping,1',

        ]);

        $tenant = TenantManager::get();

        if ($validator->passes()) {
                    
            if($request->ajax()){
                
                $this->extensionRepo->store($request->all());  

            }

            return response()->json([ 'success' => 'Extension Successfully Saved']);

        }


        return response()->json(['error'=>$validator->errors()->all()], 422);
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $extens = Extension::get();
        return view('app.tenant.media-services.exten.partials.table', compact('extens'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain, $id)
    {
        $exten = Extension::company()->where('number',$id)->get()->first();
        $devices = DeviceBrand::get();
        $users = User::company()->get();
        $extens = Extension::company()->where('type', 'sip_profile')->get();
        $numbers = Number::company()->get();
        $pilot_lines = PilotLine::company()->get();

        if(!$exten){

            abort(404);
        }

        return view('app.tenant.media-services.exten.show', compact('exten', 'extens', 'devices', 'users', 'numbers', 'pilot_lines'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($domain, $id)
    {
        $exten = Extension::company()->where('id', $id)->get()->first();
        $extens = Extension::company()->get();
        return view('app.tenant.media-services.exten.partials.edit-form', compact('exten', 'extens'));
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
            'exten_id' => 'required',
            'user_id' => 'required',
            'extension' => ['required', 'numeric', Rule::unique('call_flows', 'dial_string')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })->ignore($request->flow_id), 'min:1000', 'max:99999' ],
            'voicemail_pin' => 'required_if:voicemail,1',
            'full_monitoring_pin' => 'required_if:full_monitoring,1',
            'whispering_pin' => 'required_if:whispering,1',
            'eavesdropping' => 'required_if:eavesdropping,1',

        ]);

        $tenant = TenantManager::get();

        if ($validator->passes()) {

            $exten = Extension::find($request->exten_id);
            
            if(!$exten){ return response()->json(['error'=>['Extension not found'] ], 422); }
            if($request->ajax()){
                
                $this->extensionRepo->update($request->all());              

            }
            
            return response()->json([ 'success' => 'Extension Successfully Updated', 'url' => route('tenant.media-service.exten.show', [$domain, $exten->number]) ], 200);

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

            'exten_id' => 'required|exists:extensions,id',
            'tenant_id' => 'required|exists:tenants,id',

        ]);

        $tenant = TenantManager::get();

        $exten = Extension::company()->where('id', $request->exten_id)->get()->first();

        if(!$exten){ return response()->json(['error'=>['Extension not found'] ], 422); }

        if ($validator->passes()) {

            if($request->ajax()){
                
                if($this->extensionRepo->deletable($exten)){
                   
                    $result = $this->extensionRepo->delete($request->all());

                    return response()->json($result['status'], $result['code']);                                        
                }
                
                return response()->json(['error'=>['Extension is currently in use'] ], 422);
            }

        }


        return response()->json(['error'=>$validator->errors()->all()], 422);

    }

    public function generateFile(Extension $exten){

        $tenant = TenantManager::get();
        
        $xmlout = new \XMLWriter();
        // $xmlout->openURI('10001.xml');

        $xmlout->openMemory();
        $xmlout->setIndent(true);
        $xmlout->setIndentString('  ');
        // $xmlout->startDocument('1.0', 'UTF-8', 'no');

        //start include
        $xmlout->startElement('include');


        //start user
        $xmlout->startElement('user');
        $xmlout->writeAttribute('id', $exten->exten_reg);

        //start params
        $xmlout->startElement('params');


        $xmlout->startElement('param');
        $xmlout->writeAttribute('name', 'password');
        // $xmlout->writeAttribute('value', '$${default_password}');
        $xmlout->writeAttribute('value', $exten->password);

        //end param
        $xmlout->endElement();

        $xmlout->startElement('param');
        $xmlout->writeAttribute('name', 'vm-password');
        $xmlout->writeAttribute('value', $exten->voicemail_pin ? $exten->voicemail_pin : $exten->number);
        //end param
        $xmlout->endElement();


        //end params
        $xmlout->endElement();


        //start variables
        $xmlout->startElement('variables');

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'toll_allow');
        $xmlout->writeAttribute('value', 'domestic,international,local');
        //end variable
        $xmlout->endElement();



        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'accountcode');
        $xmlout->writeAttribute('value', $exten->exten_reg);
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'user_context');
        // $xmlout->writeAttribute('value', $exten->context);
        $xmlout->writeAttribute('value', 'default');
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'effective_caller_id_name');
        $xmlout->writeAttribute('value', $exten->name. "  ". $exten->number);
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'effective_caller_id_number');
        $xmlout->writeAttribute('value', $exten->number);
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'outbound_caller_id_name');
        $xmlout->writeAttribute('value', $tenant->name);
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'outbound_caller_id_number');
        $xmlout->writeAttribute('value', '$${outbound_caller_id}');
        //end variable
        $xmlout->endElement();

        $xmlout->startElement('variable');
        $xmlout->writeAttribute('name', 'callgroup');
        $xmlout->writeAttribute('value', 'techsupport');
        //end variable
        $xmlout->endElement();


        // end variables
        $xmlout->endElement();

        // end user
        $xmlout->endElement();

        // end include
        $xmlout->endElement();


        // $xmlout->endDocument();
        //echo $xmlout->outputMemory();

        // $xmlout->flush();

        file_put_contents('/var/www/freeswitch/'. $exten->exten_reg .'.xml', $xmlout->flush(true));

        shell_exec('chmod -f 777 /var/www/freeswitch/'. $exten->exten_reg .'.xml');

    }
}
