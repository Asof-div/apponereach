<?php

namespace App\Http\Controllers\App\Tenant\CRM;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Industry;
use App\Models\User;
use App\Helpers\AccountHelper;
use App\Helpers\CountryHelper;
use App\Helpers\PaymentTermsHelper;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use Validator;


class AccountController extends Controller
{
    public function __construct(){

        $this->account = new AccountHelper();
        $this->country = new CountryHelper();
        $this->paymentTerms = new PaymentTermsHelper();

        $this->middleware(['tenant', 'auth']);
    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = Account::orderBy('name')->get();

        return view('app.tenant.crm.account.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::orderBy('name')->get();
        $currencies = Currency::orderBy('code')->get();
        $industries = Industry::orderBy('name')->get();
        $users = User::company()->orderBy('lastname')->get();
        $types = $this->account->getTypes();
        $sources = $this->account->getSources();
        $countries = $this->country->getCountries();
        $payment_terms = $this->paymentTerms->getTerms();

        return view('app.tenant.crm.account.create', compact('accounts', 'countries', 'currencies', 'industries', 'sources', 'types', 'users', 'payment_terms'));
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
            
            'owner' => 'required',
            'name' => ['required', Rule::unique('accounts', 'name')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                }), 'min:2', 'max:255' ],
            'country' => 'required',
            'notes' => 'max:65530',
            'industry' => 'required',
            'phone' => 'required_without_all:email',
            'email' => 'required_without_all:phone',

        ]);

        $tenant = TenantManager::get();

        if ($validator->passes()) {
                    
            if($request->ajax()){
                $account = Account::create([
                    'name' => ucwords($request->name),
                    'category' => $request->type,
                    'tenant_id' => $tenant->id,
                    'email' => $request->email,
                    'vat_number' => $request->vat_number,
                    // 'id_number' => $request->id_number,
                    'payment_terms' => $request->payment_terms,
                    'postcode' => $request->postcode,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country' => $request->country,
                    'phone' => $request->phone,
                    'created_by' => Auth::id(),
                    'note' => $request->notes,
                    'account_manager' => $request->owner,
                    'source' => $request->source,
                    'website' => $request->website,
                    'industry_id' => $request->industry,
                    'currency_id' => $request->currency,
                ]);
  
                
                return response()->json([ 'success' => 'Account Successfully Saved', 'accounts' => Account::orderBy('name')->get(), 'account' => $account]);

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
    public function show($domain, $name)
    {
        $account = Account::where('name', $name)->with(['quotes', 'opportunities', 'invoices', 'contacts'])->get()->first();
        $currencies = Currency::orderBy('code')->get();
        $industries = Industry::orderBy('name')->get();
        $users = User::company()->orderBy('lastname')->get();
        $types = $this->account->getTypes();
        $sources = $this->account->getSources();
        $countries = $this->country->getCountries();
        $payment_terms = $this->paymentTerms->getTerms();
        $payment = $this->paymentTerms;
        $contacts = $account->contacts;
        $cdrs = collect();
        foreach ($contacts as $contact) {
            $cdrs = $cdrs->merge($contact->cdrs());
        }

        if(!$account){

            abort(404);
        }

        return view('app.tenant.crm.account.show', compact('account', 'countries', 'currencies', 'industries', 'sources', 'types', 'users', 'payment_terms', 'payment', 'cdrs'));
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
            
            'account_id' => 'required|exists:accounts,id',
            'name' => ['required', Rule::unique('accounts', 'name')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })->ignore($request->account_id), 'min:2', 'max:255' ],
            'owner' => 'required',
            'country' => 'required',
            'notes' => 'required|min:10|max:65530',
            'industry' => 'required',
            'phone' => 'required_without_all:email',
            'email' => 'required_without_all:phone',
            'country' => 'required',

        ]);
        
        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all()], 422);
        $tenant = TenantManager::get();

        if ($validator->passes()) {

            $account = Account::find($request->account_id);
                    
            if($request->ajax() && $account){
                    
                $account->update([
                    'name' => ucwords($request->name),
                    'category' => $request->type,
                    'vat_number' => $request->vat_number,
                    // 'id_number' => $request->id_number,
                    'email' => $request->email,
                    'postcode' => $request->postcode,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country' => $request->country,
                    'phone' => $request->phone,
                    'note' => $request->notes,
                    'account_manager' => $request->owner,
                    'source' => $request->source,
                    'website' => $request->website,
                    'industry_id' => $request->industry,
                    'currency_id' => $request->currency,
                    'payment_terms' => $request->payment_terms,
                ]);
  

            }


            return response()->json([ 'success' => 'Account Successfully Updated', 'url' => route('tenant.crm.account.show', [$domain, $account->name])]);
        }

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
}
