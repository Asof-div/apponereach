<?php

namespace App\Http\Controllers\App\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Operator\PilotNumber;
use App\Models\Tenant;

use App\Services\PilotNumberPurchaseService;
use App\Models\LineType;
use App\Exports\PilotNumberExport;
use App\Imports\PilotNumberImport;
use Maatwebsite\Excel\Facades\Excel;

use Auth;
use Validator;
// use Excel;

class PilotNumberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:operator');
        $this->pilotNumberService = new PilotNumberPurchaseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pilot_numbers = PilotNumber::paginate(500);

        return view('app.operator.pilot_number.index', compact('pilot_numbers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lineTypes = LineType::get();

        return view('app.operator.pilot_number.create', compact('lineTypes'));
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
            
            'number' => 'required|unique:pilot_numbers,number|digits:11|numeric|phone_number',
            'type' => 'required',

        ]);

        if ($validator->passes()) {
            $last = PilotNumber::get()->last();
            $batch = $last ? $last->batch : 1;
            if($request->ajax()){
            
                PilotNumber::create([
                    'number' => $request->number,
                    'available' => 1,
                    'serial_no' => $request->serial_no,
                    'source' => 'Operator',
                    'type_id' => $request->type,
                    'batch' => $batch,
                    'operator_type' => 'App\Models\Operator',
                    'operator_id' => Auth::id(),
                    ]);

                return response()->json(['success'=>'MSISDN Successfully Saved.'], 200);

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
    public function update(Request $request)
    {
        //
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {

        return Excel::download(new PilotNumberExport, 'pilot_number.xlsx');
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function template(Request $request)
    {
        
        $collections = (new PilotNumber)->newCollection();

        return Excel::download(new PilotNumberExport($collections), 'template.xlsx');
    }

    public function import(Request $request){

        $validator = $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);


        $csvdata = [];

        ini_set('max_execution_time', 300);

        $dataStatus = [];

        $file = $request->file;

        $filename = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();


        if($request->hasFile('file')){
            $pilotImporter = new PilotNumberImport;
            Excel::import($pilotImporter, $request->file);
            $dataStatus = $pilotImporter->getStatusMessage();
        }
        
        return redirect()->route('operator.pilot_number.create')->with(['flash_message' => 'Files Successfully Imported', 'import_status' => $dataStatus]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'pilot_id' => 'required|exists:pilot_numbers,id',
        ]);
        
        $pilot = PilotNumber::where('id', $request->pilot_id)->where('tenant_id', null)->first();
        if(!$pilot){

            return redirect()->route('operator.pilot_number.index')->withErrors(['This pilot number cannot be deleted because it belongs to a customer.']);
        }
        $pilot->delete();

        return redirect()->route('operator.pilot_number.index')->with('flash_message', 'Pilot Number successfully deleted');
    }

    public function search(Request $request){

        $customer = Tenant::find($request->customer_id);
        if($customer){

           $resolve = $this->pilotNumberService->search($customer, $request->all()); 
        
           return response()->json($resolve);
        }

        return response()->json(['status' => 'error', 'error' => 'Customer id not found', 'numbers' => []]);
    }

    public function addToCart(Request $request){

        $customer = Tenant::find($request->customer_id);
        if($customer){

           $resolve = $this->pilotNumberService->addToCart($customer, $request->all()); 
        
           return response()->json($resolve);
        }

        return response()->json(['status' => 'error', 'error' => 'Customer id not found', 'numbers' => []]);
    }

    public function removeFromCart(Request $request){

        $customer = Tenant::find($request->customer_id);
        if($customer){

           $resolve = $this->pilotNumberService->removeFromCart($customer, $request->all()); 
        
           return response()->json($resolve);
        }

        return response()->json(['status' => 'error', 'error' => 'Customer id not found', 'numbers' => []]);
    }

    public function importDataToDB($data, &$dataStatus){

        if (!empty($data) && count($data)) {

            foreach ($data as $key => $row) {
                if(is_array($row) && array_key_exists('msisdn' ,array_values($row) )  ){

                    $this->importDataToDB($row);

                }
                else{

                    $key_state = (int) $key + 1;

                    // dd($row);

                    if(isset($row['msisdn']) ){

                        $pilot_number = PilotNumber::where('number', $row['msisdn'])->first();

                        if(!$pilot_number){


                            PilotNumber::Create([
                                'number' => $row['msisdn'], 
                                'serial_no' => array_key_exists('serial_no', $row) ? $this->processString($row['serial_no']) : null,
                                'available' => 1,
                                'source' => 'Operator',
                                'operator_type' => 'App\Models\Operator',
                                'operator_id' => Auth::id(),
                            ]);
                            

                            $dataStatus[] = ['msg' => 'Row '.$key_state.' -  MSISDN - '. $row['msisdn'] .' Successfully Imported.', 'status' => 'success'];
                        }

                        
                    }else{

                        $dataStatus[] = ['msg' => 'Row '.$key_state. ' - MSISDN - '. $row['msisdn'] .' Already existing.', 'status' => 'failed' ]; 

                    }
                    
                }

            }
            
        }
        else{

            $dataStatus[] = ['msg' => "File Has No Data !!!", 'status' => 'failed'];
        }
        return $dataStatus;
    }



}
