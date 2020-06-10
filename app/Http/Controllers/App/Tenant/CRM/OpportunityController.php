<?php

namespace App\Http\Controllers\App\Tenant\CRM;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Currency;
use App\Models\Industry;
use App\Models\Opportunity;
use App\Models\User;
use App\Helpers\AccountHelper;
use App\Helpers\CountryHelper;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use Validator;


class OpportunityController extends Controller
{
    public function __construct(){

        $this->account = new AccountHelper();
        $this->country = new CountryHelper();

        $this->middleware(['tenant', 'auth']);
    
    }    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $opportunities = Opportunity::company()->orderBy('created_by', 'desc')->get();

        return view('app.tenant.crm.opportunity.index', compact('opportunities') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $opportunities = Opportunity::company()->get();
        $accounts = Account::orderBy('name')->get();
        $currencies = Currency::orderBy('code')->get();
        $industries = Industry::orderBy('name')->get();
        $users = User::company()->orderBy('lastname')->get();
        $types = $this->account->getTypes();
        $sources = $this->account->getSources();
        $countries = $this->country->getCountries();

        return view('app.tenant.crm.opportunity.create', compact('opportunities', 'accounts', 'countries', 'currencies', 'industries', 'sources', 'types', 'users'));
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
            'currency' => 'required|exists:currencies,id',
            'account' => 'required',
            'description' => 'required',
            'stage' => 'required',
            'source' => 'required',

        ]);

        $tenant = TenantManager::get();

        if ($validator->passes()) {
                    
            if($request->ajax()){

                $date = new \DateTime(str_replace('/', '-', $request->close_date));

                $opportunity = Opportunity::create([
                    'title' => $request->title,
                    'tenant_id' => $tenant->id,
                    'opportunity_no' => time(),
                    'isRecurrent' => $request->repeat_order ? 1 : 0,
                    'account_id' => $request->account,
                    'stage' => $request->stage,
                    'attention' => $request->attention,
                    'probability' => $request->probability,
                    'currency_id' => $request->currency_id,
                    'description' => $request->description,
                    'manager_id' => Auth::id(),
                    'competitor_id' => $request->competitor,
                    'created_by' => Auth::id(),
                    'source' => $request->source,
                    'close_date' => $date,
                ]);
  

            }

            return response()->json([ 'success' => 'Account Successfully Saved']);
        }

        return response()->json(['error'=>$validator->errors()->all()], 422);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain, $id)
    {
        $opportunity = Opportunity::with(['lines'])->find($id);
        $opportunities = Opportunity::orderBy('title')->get();
        $contacts = Contact::company()->where('account_id', $opportunity->account_id)->get();
        $currencies = Currency::get();
        
        if(!$opportunity){

            abort(404);
        }


        return view('app.tenant.crm.opportunity.show', compact('opportunity', 'opportunities', 'contacts', 'currencies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
    public function destroy($id)
    {
        //
    }
}
