<?php

namespace App\Http\Controllers\App\Tenant\CRM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Models\Account;
use App\Models\Currency;
use App\Models\Contact;
use App\Models\Quote;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Opportunity;

use App\Services\InvoiceItemsService;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use Illuminate\Validation\Rule;

use Auth;
use Validator;
class InvoiceController extends Controller
{    
    public function __construct(){

        $this->middleware(['tenant', 'auth']);
        $this->invoiceItemService = new InvoiceItemsService();

    }
 
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::company()->orderBy('created_at')->paginate(50);

        return view('app.tenant.crm.invoice.index', compact('invoices'));
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
        $invoice = new Invoice;
        $invoice->invoice_no = $this->getInvoiceNumber();
        $invoice->deposit = 0.00;

        return view('app.tenant.crm.invoice.create', compact('accounts', 'contacts', 'currencies', 'invoice'));
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
            'invoice_no' => ['required', Rule::unique('invoices', 'invoice_no')->where(function ($query) {
                return $query->where('tenant_id', TenantManager::getTenantKey());
                })->ignore($request->invoice_id), 'min:2', 'max:255' ],
            'account' => 'required|exists:accounts,id',

        ]);

        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all(), 'invoice_no' => $this->getInvoiceNumber() ], 422);

        $response = $this->invoiceItemService->store($request->all());
     
        return response()->json($response['response'], $response['code']);    
    }

    public function status(Request $request, $domain)
    {
        $validator = Validator::make($request->all(), [

            'status' => 'required',
            'invoice_id' => 'required|exists:invoices,id',

        ]);

        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all() ], 422);

        $response = $this->invoiceItemService->status($request->all());
     
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
        
        $invoice = Invoice::company()->where('id', $id)->with(['account', 'historys'])->get()->first() ?? abort(404);

        return view('app.tenant.crm.invoice.history', compact('invoice')); 
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
        $invoice = Invoice::company()->where('id', $id)->with(['account', 'contacts'])->get()->first() ?? abort(404);
        $account = $invoice->account->load(['currency']);
        // dd($account);
        return view('app.tenant.crm.invoice.edit', compact('accounts', 'contacts', 'currencies', 'invoice', 'account'));   
    }

    public function clone($domain, Request $request, $id)
    {
        $contacts = Contact::company()->orderBy('name')->get();
        $accounts = Account::company()->with(['currency', 'contacts'])->orderBy('name')->get();
        $currencies = Currency::orderBy('code')->get();
        $clone = Invoice::company()->where('id', $id)->with(['account', 'contacts'])->get()->first() ?? abort(404);
        $invoice = new Invoice;
        $invoice->invoice_no = $this->getInvoiceNumber();
        
        $this->cloneObject($invoice, $clone);        

        return view('app.tenant.crm.invoice.clone', compact('accounts', 'contacts', 'currencies', 'invoice'));
    }


    public function cloneQuote($domain, Request $request, $id)
    {
        $contacts = Contact::company()->orderBy('name')->get();
        $accounts = Account::company()->with(['currency', 'contacts'])->orderBy('name')->get();
        $currencies = Currency::orderBy('code')->get();
        $clone = Quote::company()->where('id', $id)->with(['account', 'contacts', 'items'])->get()->first() ?? abort(404);
        $invoice = new Invoice;
        $invoice->invoice_no = $this->getInvoiceNumber();
        
        $this->cloneObjectQuote($invoice, $clone);        

        return view('app.tenant.crm.invoice.clone', compact('accounts', 'contacts', 'currencies', 'invoice'));
    }

    public function convert(Request $request, $domain)
    {
        $validator = Validator::make($request->all(), [

            'quote_id' => 'required|exists:quotes,id',

        ]);

        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all() ], 422);

        $clone = Quote::company()->where('id', $request->quote_id)->with(['account', 'contacts', 'items'])->get()->first() ?? abort(404);
        $invoice = new Invoice;
        $invoice->invoice_no = $this->getInvoiceNumber();
        $response = $this->invoiceItemService->convertQuote($invoice, $clone);
        $invoice->push();             
        $clone->update(['status' => 3]);

        return response()->json($response['response'], $response['code']);           
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

            'invoice_id' => 'required|exists:invoices,id',

        ]);

        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all() ], 422);

        $invoice = Invoice::find($request->invoice_id);
        $invoice->delete();

        return response()->json(['success' => 'Invoice Successfully Deleted', 'url' => route('tenant.crm.invoice.index', [$domain])], 200);    
    }

    private function getInvoiceNumber(){
        $invoices = Invoice::company()->get()->pluck('invoice_no');
        $num = count($invoices) + 1;
        if(!$invoices->search(sprintf("%05d", $num)) ){
            return sprintf("%05d", $num);
        }else{
            $this->random($invoices, $num);
            return sprintf("%05d", $num);
        }
        
    }

    function random($invoices, &$num){
        while($invoices->search(sprintf("%05d", $num)) !== false ){
            $num = $num + 1;
        }
    }

    function cloneObject(&$invoice, $clone){

        $invoice->title = $clone->title;
        $invoice->tenant_id = $clone->tenant_id;
        $invoice->opportunity_id = $clone->opportunity_id;
        $invoice->account_id = $clone->account_id;
        $invoice->currency_id = $clone->currency_id;
        $invoice->payment_terms = $clone->payment_terms;
        $invoice->terms = $clone->terms;
        $invoice->public_note = $clone->public_note;
        $invoice->private_note = $clone->private_note;
        $invoice->footer = $clone->footer;
        $invoice->deposit = $clone->deposit;
        $invoice->po_no = $clone->po_no;
        $invoice->discount = $clone->discount;
        $invoice->discount_type = $clone->discount_type;
        $invoice->discount_rate = $clone->discount_rate;
        $invoice->invoice_date = $clone->invoice_date;
        $invoice->expiration_date = $clone->expiration_date;
        $invoice->vat = $clone->vat;
        $invoice->vat_type = $clone->vat_type;
        $invoice->vat_rate = $clone->vat_rate;
        $invoice->sub_total = $clone->sub_total;
        $invoice->grand_total = $clone->grand_total;
        $invoice->balance_due = $clone->balance_due;
        $invoice->items = $clone->items;
        $invoice->contacts = $clone->contacts;
    }

    function cloneObjectQuote(&$invoice, $clone){

        $invoice->title = $clone->title;
        $invoice->tenant_id = $clone->tenant_id;
        $invoice->opportunity_id = $clone->opportunity_id;
        $invoice->account_id = $clone->account_id;
        $invoice->currency_id = $clone->currency_id;
        $invoice->payment_terms = $clone->payment_terms;
        $invoice->terms = $clone->terms;
        $invoice->public_note = $clone->public_note;
        $invoice->private_note = $clone->private_note;
        $invoice->footer = $clone->footer;
        $invoice->deposit = $clone->deposit;
        $invoice->po_no = $clone->po_no;
        $invoice->discount = $clone->discount;
        $invoice->discount_type = $clone->discount_type;
        $invoice->discount_rate = $clone->discount_rate;
        $invoice->invoice_date = $clone->quote_date;
        $invoice->expiration_date = $clone->expiration_date;
        $invoice->vat = $clone->vat;
        $invoice->vat_type = $clone->vat_type;
        $invoice->vat_rate = $clone->vat_rate;
        $invoice->sub_total = $clone->sub_total;
        $invoice->grand_total = $clone->grand_total;
        $invoice->balance_due = $clone->balance_due;
        $invoice->items = $clone->items;
        $invoice->contacts = $clone->contacts;
    }

    function convertObjectQuote(&$invoice, $clone){

        $invoice->title = $clone->title;
        $invoice->tenant_id = $clone->tenant_id;
        $invoice->quote_id = $clone->quote_id;
        $invoice->opportunity_id = $clone->opportunity_id;
        $invoice->account_id = $clone->account_id;
        $invoice->currency_id = $clone->currency_id;
        $invoice->payment_terms = $clone->payment_terms;
        $invoice->terms = $clone->terms;
        $invoice->public_note = $clone->public_note;
        $invoice->private_note = $clone->private_note;
        $invoice->footer = $clone->footer;
        $invoice->deposit = $clone->deposit;
        $invoice->po_no = $clone->po_no;
        $invoice->discount = $clone->discount;
        $invoice->discount_type = $clone->discount_type;
        $invoice->discount_rate = $clone->discount_rate;
        $invoice->invoice_date = $clone->quote_date;
        $invoice->expiration_date = $clone->expiration_date;
        $invoice->vat = $clone->vat;
        $invoice->vat_type = $clone->vat_type;
        $invoice->vat_rate = $clone->vat_rate;
        $invoice->sub_total = $clone->sub_total;
        $invoice->grand_total = $clone->grand_total;
        $invoice->balance_due = $clone->balance_due;
        $invoice->save();
        foreach ($clone->contacts as $contact) {
    
            $invoice->contacts()->save($contact);
    
        }
        foreach ($clone->items as $item) {
            $newItem = InvoiceItem::create([
                'tenant_id' => $item->tenant_id,
                'invoice_id' => $invoice->id,
                'name' => $item->name,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'list_order' => $item->list_order,
                'optional' => $item->optional,
                'uprice' => $item->uprice, 
                'price' => $item->price,

            ]); 
            $invoice->items()->save($newItem);
    
        }
    }


}
