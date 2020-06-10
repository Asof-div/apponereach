<?php

namespace App\Http\Controllers\App\Tenant\CRM;

use App\Http\Controllers\Controller;

use App\Models\Account;
use App\Models\Contact;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Helpers\CountryCodeHelper;

use Auth;
use Validator;

class ContactController extends Controller
{
    public function __construct(CountryCodeHelper $countryCode){

        $this->middleware(['tenant', 'auth']);
        $this->countryCode = $countryCode;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $contacts = Contact::company()->paginate(50);

        return view('app.tenant.crm.contact.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($domain, $id=null)
    {
        $contact = new Contact;
        $account = Account::company()->where('id', $id)->get()->first() ?? null;
        $accounts = Account::get();
        $countries = $this->countryCode->getCountries();

        return view('app.tenant.crm.contact.create', compact('accounts', 'contact', 'account', 'countries'));
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

            'name' => 'required|min:2|max:255',
            'account_id' => 'required|exists:accounts,id',
            'phone' => 'required_without_all:email',
            'number' => ['required_without_all:email', Rule::unique('contacts', 'number')->where(function ($query) use ($request) {      return $query->where('tenant_id', TenantManager::getTenantKey())->where('account_id', $request->account_id);
                })->ignore($request->contact_id) ],
            'country_code' => 'required_without_all:email',
            'email' => 'required_without_all:phone',
            'title' => 'required',

        ]);

        $tenant = TenantManager::get();

        if ($validator->passes()) {
                    
            if($request->ajax()){
                $contact = Contact::create([
                    'name' => $request->name,
                    'title' => $request->title,
                    'tenant_id' => $tenant->id,
                    'account_id' => $request->account_id,
                    'email' => $request->email,
                    'country_code' => $request->country_code,
                    'phone' => $request->phone,
                    'number' => $request->number,
                ]);  

            }

            return response()->json([ 'success' => 'Contact Successfully Saved']);
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

        $contact = Contact::company()->where('id', $id)->get()->first() ?? abort(404);
        $account = $contact->account;
        $accounts = Account::get();
        $countries = $this->countryCode->getCountries();

        // $regex = "/(+234)(7083191132)/";
        // $string = '7083191132';
        // preg_match($regex, $string, $match);
        // dd($match);
        if(!$contact){

            abort(404);
        }

        return view('app.tenant.crm.contact.show', compact('acounts', 'account', 'contact', 'countries'));
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
        $validator = Validator::make($request->all(), [

            'contact_id' => 'required|exists:contacts,id',
            'account_id' => 'required|exists:accounts,id',
            'number' => ['required_without_all:email', Rule::unique('contacts', 'number')->where(function ($query) use ($request) {      return $query->where('tenant_id', TenantManager::getTenantKey())->where('account_id', $request->account_id);
                })->ignore($request->contact_id) ],
            'phone' => 'required_without_all:email',
            'country_code' => 'required_without_all:email',
            'email' => 'required_without_all:phone',
            'title' => 'required',

        ]);

        $tenant = TenantManager::get();

        if ($validator->passes()) {
                    
            if($request->ajax()){
                $contact = Contact::find($request->contact_id);
                $contact->update([
                    'name' => $request->name,
                    'title' => $request->title,
                    'tenant_id' => $tenant->id,
                    'account_id' => $request->account_id,
                    'email' => $request->email,
                    'country_code' => $request->country_code,
                    'phone' => $request->phone,
                    'number' => $request->number,
                ]);  

            }

            return response()->json([ 'success' => 'Contact Successfully Updated']);
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

            'contact_id' => 'required|exists:contacts,id',

        ]);

        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all() ], 422);
        
        $contact = Contact::find($request->contact_id);

        if($contact){
            $contact->delete();
            return response()->json(['success' => 'Contact Successfully Deleted', 'url' => route('tenant.crm.contact.index', [$domain])], 200);    
        }
        
        return response()->json(['error' => ['Contact not found']], 422);
    }
}
