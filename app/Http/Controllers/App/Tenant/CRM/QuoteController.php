<?php

namespace App\Http\Controllers\App\Tenant\CRM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Account;
use App\Models\Currency;
use App\Models\Contact;
use App\Models\Quote;
use App\Models\Invoice;
use App\Models\QuoteItem;
use App\Models\Opportunity;

use App\Services\QuoteItemsService;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use Illuminate\Validation\Rule;

use Auth;
use Validator;

class QuoteController extends Controller
{
    public function __construct(){

        $this->middleware(['tenant', 'auth']);
        $this->quoteItemService = new QuoteItemsService();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotes = Quote::company()->orderBy('created_at')->paginate(50);
        
        return view('app.tenant.crm.quote.index', compact('quotes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($domain, Request $request)
    {
        $contacts = Contact::company()->orderBy('name')->get();
        $accounts = Account::company()->with(['currency', 'contacts'])->orderBy('name')->get();
        $currencies = Currency::orderBy('code')->get();
        $quote = new Quote;
        $quote->quote_no = $this->getQuoteNumber();
        $quote->deposit = 0.00;

        return view('app.tenant.crm.quote.create', compact('accounts', 'contacts', 'currencies', 'quote'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $domain)
    {
        $validator = Validator::make($request->all(), [

            'title' => ['required', 'min:5', 'max:255' ],
            'currency_id' => 'required|exists:currencies,id',
            'quote_no' => ['required', Rule::unique('quotes', 'quote_no')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })->ignore($request->quote_id), 'min:2', 'max:255' ],
            'account' => 'required|exists:accounts,id',

        ]);

        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all(), 'quote_no' => $this->getQuoteNumber()], 422);

        $response = $this->quoteItemService->store($request->all());
     
        return response()->json($response['response'], $response['code']);    
    }

    public function status(Request $request, $domain)
    {
        $validator = Validator::make($request->all(), [

            'status' => 'required',
            'quote_id' => 'required|exists:quotes,id',

        ]);

        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all() ], 422);

        $response = $this->quoteItemService->status($request->all());
     
        return response()->json($response['response'], $response['code']);    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function history($domain, $id, Request $request)
    {
        
        $quote = Quote::company()->where('id', $id)->with(['account', 'historys'])->get()->first() ?? abort(404);

        // dd($quote->historys);

        return view('app.tenant.crm.quote.history', compact('quote')); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($domain, $id, Request $request)
    {
        $contacts = Contact::company()->orderBy('name')->get();
        $accounts = Account::company()->with(['currency', 'contacts'])->orderBy('name')->get();
        $currencies = Currency::orderBy('code')->get();
        $quote = Quote::company()->where('id', $id)->with(['account', 'contacts'])->get()->first() ?? abort(404);
        $account = $quote->account->load(['currency']);
        // dd($account);
        return view('app.tenant.crm.quote.edit', compact('accounts', 'contacts', 'currencies', 'quote', 'account'));   
    }

    public function createAccount($domain, $id, Request $request)
    {
        $contacts = Contact::company()->orderBy('name')->get();
        $accounts = Account::company()->with(['currency', 'contacts'])->orderBy('name')->get();
        $account = Account::company()->with(['currency', 'contacts'])->where('id', $id)->get()->first() ?? abort(404);
        $currencies = Currency::orderBy('code')->get();
        $quote = new Quote;
        $quote->account_id = $account->id;
        $quote->tenant_id = $account->tenant_id;
        $quote->currency_id = $account->currency_id;
        $quote->status = 1;
        $quote->quote_no = $this->getQuoteNumber();
        $quote->deposit = 0.00;
        return view('app.tenant.crm.quote.create_account', compact('accounts', 'contacts', 'currencies', 'quote', 'account'));   
    }

    public function clone($domain, Request $request, $id)
    {
        $contacts = Contact::company()->orderBy('name')->get();
        $accounts = Account::company()->with(['currency', 'contacts'])->orderBy('name')->get();
        $currencies = Currency::orderBy('code')->get();
        $clone = Quote::company()->where('id', $id)->with(['account', 'contacts'])->get()->first() ?? abort(404);
        $quote = new Quote;
        $quote->quote_no = $this->getQuoteNumber();
        
        $this->cloneObject($quote, $clone);        

        return view('app.tenant.crm.quote.clone', compact('accounts', 'contacts', 'currencies', 'quote'));
    }

    public function cloneInvoice($domain, Request $request, $id)
    {
        $contacts = Contact::company()->orderBy('name')->get();
        $accounts = Account::company()->with(['currency', 'contacts'])->orderBy('name')->get();
        $currencies = Currency::orderBy('code')->get();
        $clone = Invoice::company()->where('id', $id)->with(['account', 'contacts'])->get()->first() ?? abort(404);
        $quote = new Quote;
        $quote->quote_no = $this->getQuoteNumber();

        $this->cloneObjectInvoice($quote, $clone);        

        return view('app.tenant.crm.quote.clone', compact('accounts', 'contacts', 'currencies', 'quote'));
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

            'quote_id' => 'required|exists:quotes,id',

        ]);

        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all() ], 422);
        $tenant = TenantManager::get();

        $quote = Quote::find($request->quote_id);
        $quote->historys()->delete();
        $quote->items()->delete();
        $quote->contacts()->detach();
        $quote->delete();

        $storage_path = storage_path('public/'.$tenant->tenant_no."/quote/{$quote->quote_no}");

        shell_exec('rm -r '. $storage_path);

        return response()->json(['success' => 'Quote Successfully Deleted', 'url' => route('tenant.crm.quote.index', [$domain])], 200);    
    }

    private function getQuoteNumber(){
        $quotes = Quote::company()->get()->pluck('quote_no');

        $num = count($quotes) + 1;
        if(!$quotes->search(sprintf("%05d", $num)) ){
            return sprintf("%05d", $num);
        }else{
            $this->random($quotes, $num);
            return sprintf("%05d", $num);
        }
        
    }

    function random($quotes, &$num){
        while($quotes->search(sprintf("%05d", $num)) !== false ){
            $num = $num + 1;
        }
    }

    function cloneObject(&$quote, $clone){

        $quote->title = $clone->title;
        $quote->tenant_id = $clone->tenant_id;
        $quote->opportunity_id = $clone->opportunity_id;
        $quote->account_id = $clone->account_id;
        $quote->currency_id = $clone->currency_id;
        $quote->payment_terms = $clone->payment_terms;
        $quote->terms = $clone->terms;
        $quote->public_note = $clone->public_note;
        $quote->private_note = $clone->private_note;
        $quote->footer = $clone->footer;
        $quote->deposit = $clone->deposit;
        $quote->po_no = $clone->po_no;
        $quote->discount = $clone->discount;
        $quote->discount_type = $clone->discount_type;
        $quote->discount_rate = $clone->discount_rate;
        $quote->quote_date = $clone->quote_date;
        $quote->expiration_date = $clone->expiration_date;
        $quote->vat = $clone->vat;
        $quote->vat_type = $clone->vat_type;
        $quote->vat_rate = $clone->vat_rate;
        $quote->sub_total = $clone->sub_total;
        $quote->grand_total = $clone->grand_total;
        $quote->balance_due = $clone->balance_due;
        $quote->items = $clone->items;
        $quote->contacts = $clone->contacts;
    }

    function cloneObjectInvoice(&$quote, $clone){

        $quote->title = $clone->title;
        $quote->tenant_id = $clone->tenant_id;
        $quote->opportunity_id = $clone->opportunity_id;
        $quote->account_id = $clone->account_id;
        $quote->currency_id = $clone->currency_id;
        $quote->payment_terms = $clone->payment_terms;
        $quote->terms = $clone->terms;
        $quote->public_note = $clone->public_note;
        $quote->private_note = $clone->private_note;
        $quote->footer = $clone->footer;
        $quote->deposit = $clone->deposit;
        $quote->po_no = $clone->po_no;
        $quote->discount = $clone->discount;
        $quote->discount_type = $clone->discount_type;
        $quote->discount_rate = $clone->discount_rate;
        $quote->quote_date = $clone->invoice_date;
        $quote->expiration_date = $clone->expiration_date;
        $quote->vat = $clone->vat;
        $quote->vat_type = $clone->vat_type;
        $quote->vat_rate = $clone->vat_rate;
        $quote->sub_total = $clone->sub_total;
        $quote->grand_total = $clone->grand_total;
        $quote->balance_due = $clone->balance_due;
        $quote->items = $clone->items;
        $quote->contacts = $clone->contacts;
    }
}
