<?php

namespace App\Http\Controllers\Api\Tenant\CRM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Response\ApiResponse;

use App\Models\Account;
use App\Models\Currency;
use App\Models\Contact;
use App\Models\Quote;
use App\Models\Invoice;
use App\Models\QuoteItem;
use App\Models\Opportunity;

use App\Services\CRM\QuoteItemsService;
use App\Http\Resources\QuoteCollection;

use Illuminate\Validation\Rule;

use Auth;
use Validator;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $quotes = new QuoteCollection( Quote::company($request->user()->tenant_id)->orderBy('created_at')->with(['account', 'historys'])->get() );
        
        return response()->json(['quotes' => $quotes], 200);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, QuoteItemsService $quoteItemService)
    {
        $validator = Validator::make($request->all(), [

            'title' => ['required', 'min:3', 'max:255' ],
            'currency_id' => 'required|exists:currencies,id',
            // 'quote_no' => ['required', Rule::unique('quotes', 'quote_no')->where(function ($query) {
            //     return $query->where('tenant_id', request()->user()->tenant_id );
            //     })->ignore($request->quote_id), 'min:2', 'max:255' ],
            'account_id' => 'required|exists:accounts,id',

        ]);

        
        $tenant = request()->user()->tenant;
        $user = request()->user();

        if ($validator->fails()){
            $response = (new ApiResponse)->error($validator->errors());
            return response()->json($response, 422);
        }

        $response = $quoteItemService->store($tenant, $request->all());
     
        return response()->json($response['response'], $response['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        
        $quote = Quote::company($request->user()->tenant_id)->where('id', $id)->with(['account', 'contacts'])->first();
        
        if(!$quote){

            $response = (new ApiResponse)->error('Quote does not exist');
            return response()->json($response, 403);
        }
     
        return response()->json(['quote' => $quote], 200);

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
    public function delete($id, Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [

            'quote_id' => 'required|exists:quotes,id',

        ]);

        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all() ], 422);
        $tenant = $user->tenant;

        $quote = Quote::find($request->quote_id);
        $quote->historys()->delete();
        $quote->items()->delete();
        $quote->contacts()->detach();
        $quote->delete();

        $storage_path = storage_path('public/'.$tenant->tenant_no."/quote/{$quote->quote_no}");

        shell_exec('rm -r '. $storage_path);

        return response()->json(['success' => 'Quote Successfully Deleted', 'url' => route('tenant.crm.quote.index', [$domain])], 200);    
    }
}
