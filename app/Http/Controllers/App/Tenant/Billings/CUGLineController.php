<?php

namespace App\Http\Controllers\App\Tenant\Billings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CloseGroup;
use App\Models\PilotLine;
use App\Models\Number;
use Illuminate\Validation\Rule;

use App\Services\ManageCUGNumber;

use Validator;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

class CUGLineController extends Controller
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
    public function index()
    {
        


    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addNumber($domain, Request $request)
    {

        $validator = Validator::make($request->all(), [

            'number' => ['required', 'string', Rule::unique('numbers')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                }) ],
        ]);

        $tenant = TenantManager::get();


        if ($validator->passes()) {

            if($request->ajax()){
                

                if($this->checkNumber($request->number)){
                   
                    $result = $this->cugManager->add($request->all());

                    if(is_array($result) && isset($result['status']) && $result['status'] == 'success'){

                        return response()->json(['success'=>'Number Successfully Added', 'numbers' => $result['numbers'] ]);


                    }elseif (is_array($result) && isset($result['status']) && $result['status'] == 'exceed_limit'){


                        return response()->json(['error' => ['You have exceed Number MSISDN limit.'] ], 422);

                    }
                    
                    
                                        
                }

                return response()->json(['error'=>['Invalid Phone Number & CUG ID'] ]);
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
    public function sync($domain, Request $request)
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
        //
    }


    public function checkNumber($number){

        $pilot_lines = PilotLine::where('number', $number)->get();


        return $pilot_lines->count() > 0 ? false : true;

    }

}
