<?php

namespace App\Http\Controllers\App\Tenant\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Number;
use App\Models\User;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use App\Services\ManageCUGNumber;

use Illuminate\Validation\Rule;

use Validator;

class NumberController extends Controller
{

    public function __construct(){
        
        $this->middleware(['tenant','auth']);
        $this->cugManager = new ManageCUGNumber(TenantManager::get());

    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($domain)
    {
        $tenant = TenantManager::get();   
        $msisdn_limit = (int)$tenant->package->msisdn_limit;
        $extra_number = (int)$tenant->info->extra_number;
        $numbers = collect([]);
        $all_numbers = Number::company()->get();

        $all_numbers = $all_numbers->map(function ($number) {
            $number['empty'] = 0;
            return $number;
        });
        $remain = (int)$msisdn_limit - $all_numbers->where('slot',0)->count() ;

        for ($i=0; $i < $remain ; $i++) { 
            $all_numbers->push(new Number(['slot' => 0, 'empty' => 1]));
        }

        
        $remain = (int)$extra_number - $all_numbers->where('slot',1)->count() ;

        for ($i=0; $i < $remain ; $i++) { 
            $all_numbers->push(new Number(['slot' => 1, 'empty' => 1]));

        }
   
        $numbers = $all_numbers->sortBy('slot');
        $users = User::company()->get();

        return view('app.tenant.media-services.number.index', compact('numbers', 'msisdn_limit', 'extra_number', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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

            'name' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'number' => 'required|numeric',
            'number' => ['required', 'string', Rule::unique('numbers')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                }) ],
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        $tenant = TenantManager::get();


        if ($validator->passes()) {

            if($request->ajax()){
                

               if($this->cugManager->numberValidation($request->number)){
                   
                    $result = $this->cugManager->add($request->all());

          
                    return response()->json($result);                    
                                        
                }

                return response()->json(['error'=>['Invalid Phone Number & CUG ID'] ], 422);
            }

        }


        return response()->json(['error'=>$validator->errors()->all()]);
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
    public function update(Request $request, $domain)
    {
        
        $validator = Validator::make($request->all(), [

            'name' => 'required|string',
            'number_id' => 'required|exists:numbers,id',
            'user_id' => 'required|exists:users,id',
            'number' => 'required|numeric',
            'number' => ['required', 'string', Rule::unique('numbers')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })->ignore($request->number_id) ],
            'tenant_id' => 'required|exists:tenants,id',
            
        ]);

        $tenant = TenantManager::get();


        if ($validator->passes()) {

            if($request->ajax()){
                

                if($this->cugManager->numberValidation($request->number)){
                   
                    $result = $this->cugManager->update($request->all());

                    
                    return response()->json($result);                  
                                        
                }

                return response()->json(['error'=>['Invalid Phone Number & CUG ID'] ], 422);
            }

        }


        return response()->json(['error'=>$validator->errors()->all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function features(Request $request, $domain)
    {
        
        $validator = Validator::make($request->all(), [

            'number_id' => 'required|exists:numbers,id',
            'voicemail' => 'required_if:type,voicemail',
            'scode' => ['required_if:type,scode', 'string', Rule::unique('numbers', 'scode')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })->ignore($request->number_id) ],
    
            'recording' => 'required_if:type,recoding',
            'follow_me' => 'required_if:type,follow_me',
        ]);

        $tenant = TenantManager::get();


        if ($validator->passes()) {

            if($request->ajax()){
                
                if($request->type == 'scode'){

                    $number = Number::find($request->number_id);
                    $number ? $number->update(['scode' => $request->scode]) : '';
                    return response()->json(['success' => 'Short Code Successfully Updated']);
                }
                

                return response()->json(['error'=>['Invalid Phone Number & CUG ID'] ], 422);
            }

        }


        return response()->json(['error'=>$validator->errors()->all()]);
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

            'number_id' => 'required|exists:numbers,id',
            'tenant_id' => 'required|exists:tenants,id',

        ]);

        $tenant = TenantManager::get();

        $number = Number::company()->where('id', $request->number_id)->get()->first();

        if(!$number){ return response()->json(['error'=>['Number not found'] ], 422); }

        if ($validator->passes()) {

            if($request->ajax()){
                
                if($this->cugManager->deletable($number)){
                   
                    $result = $this->cugManager->delete($request->all());

                    return response()->json($result['status'], $result['code']);                                        
                }
                
                return response()->json(['error'=>['Number is currently in use'] ], 422);
            }

        }


        return response()->json(['error'=>$validator->errors()->all()], 422);

    }
}
