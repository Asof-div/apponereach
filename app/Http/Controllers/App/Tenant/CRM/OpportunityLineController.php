<?php

namespace App\Http\Controllers\App\Tenant\CRM;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Currency;
use App\Models\Opportunity;
use App\Models\OpportunityLine;
use App\Models\User;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use App\Repos\OpportunityItemsRepo;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use Validator;

class OpportunityLineController extends Controller
{
    public function __construct(){

    
        $this->middleware(['tenant', 'auth']);
        $this->opportunityItemsRepo = new OpportunityItemsRepo();
    }  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($domain, Request $request)
    {
        

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

            'title' => ['required', 'min:5', 'max:255' ],
            'opportunity_id' => 'required|exists:opportunities,id',
            'currency' => 'required|exists:currencies,id',
            'description' => 'required',
            'worth' => 'required',
            'contact_person' => 'required|exists:contacts,id',

        ]);

        $tenant = TenantManager::get();

        if ($validator->passes()) {
                    
            if($request->ajax()){

                $opportunity = OpportunityLine::create([
                    'name' => $request->title,
                    'tenant_id' => $tenant->id,
                    'opportunity_id' => $request->opportunity_id,
                    'worth' =>  preg_replace("/([^0-9\\.])/i", "",$request->worth),
                    'currency_id' => $request->currency,
                    'contact_id' => $request->contact_person,
                    'description' => $request->description,
                    'created_by' => Auth::id(),
                ]);
  

            }

            return response()->json([ 'success' => 'Opportunity Line Successfully Added', 'lines' => OpportunityLine::company()->with(['contact', 'currency'])->where('opportunity_id', $request->opportunity_id)->get() ]);
        }

        return response()->json(['error'=>$validator->errors()->all()], 422);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain, $id, $line, Request $request)
    {
        $opportunity = Opportunity::find($id);

        $opportunity_line = OpportunityLine::with(['items'])->find($line);
        
        if(!$opportunity | !$opportunity_line){

            abort(404);
        }
        $account = $opportunity->account;
        $currencies = Currency::orderBy('code')->get();
        $contacts = $account->contacts;

        return view('app.tenant.crm.opportunity_line.show', compact('opportunity', 'account', 'contacts', 'currencies', 'opportunity_line'));

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
    public function items($domain, $id, $line, Request $request)
    {
        
        $validator = Validator::make($request->all(), [

            'description.*' => ['required', 'min:5', 'max:255' ],
            'opportunity_line_id' => 'required|exists:opportunity_lines,id',
            'quantity.*' => 'required',
            'vat_rate' => 'required',
            'vat_type' => 'required',
            'discount_rate' => 'required',
            'discount_type' => 'required',

        ]);

        $tenant = TenantManager::get();

        if ($validator->passes()) {
                    
            if($request->ajax()){

                $this->opportunityItemsRepo->store($request->all());
  

            }

            return response()->json([ 'success' => 'Items Successfully Saved' ]);
        }

        return response()->json(['error'=>$validator->errors()->all()], 422);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}
