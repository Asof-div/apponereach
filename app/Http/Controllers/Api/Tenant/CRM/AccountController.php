<?php

namespace App\Http\Controllers\Api\Tenant\CRM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Response\ApiResponse;
use App\Models\Account;
use Validator;
use Illuminate\Validation\Rule;
use Auth;

class AccountController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/app/crm/accounts",
     *      operationId="getAccountList",
     *      tags={"CRM Account"},
     *      summary="Get Customers ",
     *      description="Returns customers list",
     *      
     *      @OA\Response(
     *          response=200,
     *          description="Customers"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     */
    public function index(Request $request)
    {
        $accounts = Account::company($request->user()->tenant_id)->with(['category', 'country', 'state', 'industry', 'manager', 'currency', 'payment_term', 'source', 'contacts'])->orderBy('name')->get();
      
        return response()->json(['accounts' => $accounts], 200);
    }

    /**
     * @OA\Post(
     *      path="/api/app/crm/accounts",
     *      operationId="creteNewAccount",
     *      tags={"CRM Account"},
     *      summary="Create new customer account",
     *      description="Returns account data",
     *      @OA\Parameter(
     *          name="account_mananger",
     *          description="Drop down list of users ",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="name",
     *          description="Customer/accoount name",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="country_id",
     *          description="list of country id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="private_note",
     *          description="private information about the customer",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="public_note",
     *          description="public information about the customer",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="industry_id",
     *          description="list of instustries id from industry resource",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          description="contact email",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="phone",
     *          description="phone number",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="payment_term_id",
     *          description="list of payment terms id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="account_source_id",
     *          description="list of account source id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="account_category_id",
     *          description="list of account category id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="address",
     *          description="customer address",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="state_id",
     *          description="list of state id",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="vat_number",
     *          description="vat number",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="postcode",
     *          description="postcode",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="webasite",
     *          description="customer web link",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="currency_id",
     *          description="list of currency id",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="id"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="account successfully created"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *     )
     *
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'account_manager' => 'required',
            'name' => ['required', Rule::unique('accounts', 'name')->where(function ($query) {
                return $query->where('tenant_id', request()->user()->tenant_id);
                }), 'min:2', 'max:255' ],
            'country_id' => 'required|exists:countries,id',
            'private_note' => 'max:65530',
            'public_note' => 'max:65530',
            'industry_id' => 'required',
            'phone' => 'required_without_all:email',
            'email' => 'required_without_all:phone',
            'payment_term_id' =>'required',
            'account_source_id' => 'required',
            'account_category_id' => 'required',

        ]);

        $tenant = request()->user()->tenant;
        $user = request()->user();


        if ($validator->fails()){
        
            $response = (new ApiResponse)->error($validator->errors()->all());
            return response()->json($response, 422);
        }
        

        $account = Account::create([
            'name' => ucwords($request->name),
            'account_category_id' => $request->account_category_id,
            'tenant_id' => $tenant->id,
            'email' => $request->email,
            'vat_number' => $request->vat_number,
            'payment_term_id' => $request->payment_term_id,
            'postcode' => $request->postcode,
            'address' => $request->address,
            'city' => $request->city,
            'state_id' => $request->state_id,
            'country_id' => $request->country_id,
            'phone' => $request->phone,
            'created_by' => Auth::id(),
            'private_note' => $request->private_note,
            'public_note' => $request->public_note,
            'account_manager' => $request->account_manager,
            'account_source_id' => $request->account_source_id,
            'website' => $request->website,
            'industry_id' => $request->industry_id,
            'currency_id' => $request->currency_id,
        ]);

        $response = (new ApiResponse)->success(['account' => $account]);
                
        return response()->json($response, 200);

    }


    /**
     * @OA\Get(
     *      path="/api/app/crm/accounts/{id}",
     *      operationId="getAccountById",
     *      tags={"CRM Account"},
     *      summary="Get Account Information",
     *      description="Returns account deta",
     *      @OA\Parameter(
     *          name="id",
     *          description="Account Id ",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successfully Operattion"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *     )
     *
     */
    public function show($id)
    {
        $account = Account::with(['category', 'country', 'state', 'industry', 'manager', 'currency', 'payment_term', 'source', 'contacts', 'quotes', 'opportunities', 'invoices'])->find($id);

        if(!$account){

            $response = (new ApiResponse)->error('customer account does not exist');
            return response()->json($response, 403);
        }

        return response()->json(['account' => $account], 200);
    }



    /**
     * @OA\Put(
     *      path="/api/app/crm/accounts/{id}",
     *      operationId="udateAccountInformation",
     *      tags={"CRM Account"},
     *      summary="Update customer Information",
     *      description="Returns updated customer data",
     *      
     *      @OA\Parameter(
     *          name="account_mananger",
     *          description="User id ",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="name",
     *          description="Customer/accoount name",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="country_id",
     *          description="list of country id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="private_note",
     *          description="private information about the customer",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="public_note",
     *          description="public information about the customer",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="industry_id",
     *          description="list of instustries id from industry resource",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          description="contact email",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="phone",
     *          description="phone number",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="payment_term_id",
     *          description="list of payment terms id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="account_source_id",
     *          description="list of account source id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="account_category_id",
     *          description="list of account category id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="address",
     *          description="customer address",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="state_id",
     *          description="list of state id",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="vat_number",
     *          description="vat number",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="postcode",
     *          description="postcode",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="website",
     *          description="customer web link",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="currency_id",
     *          description="list of currency id",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="id"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *     )
     *
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'name' => ['required', Rule::unique('accounts', 'name')->where(function ($query) {
                return $query->where('tenant_id', request()->user()->tenant_id);
                })->ignore($id), 'min:2', 'max:255' ],
            'account_manager' => 'required',
            'country_id' => 'required|exists:countries,id',
            'private_note' => 'max:65530',
            'public_note' => 'max:65530',
            'industry_id' => 'required',
            'phone' => 'required_without_all:email',
            'email' => 'required_without_all:phone',
            'payment_term_id' =>'required',
            'account_source_id' => 'required',
            'account_category_id' => 'required',
        ]);
        
        $account = Account::find($id);
        $tenant = $request->user()->tenant;


        if ($validator->fails()){
        
            $response = (new ApiResponse)->error($validator->errors()->all());
            return response()->json($response, 422);
        }
        

        if(!$account){

            $response = (new ApiResponse)->error('customer account does not exist');
            return response()->json($response, 403);
        }

        $account->update([
            'name' => ucwords($request->name),
            'account_category_id' => $request->account_category_id,
            'tenant_id' => $tenant->id,
            'email' => $request->email,
            'vat_number' => $request->vat_number,
            'payment_term_id' => $request->payment_term_id,
            'postcode' => $request->postcode,
            'address' => $request->address,
            'city' => $request->city,
            'state_id' => $request->state_id,
            'country_id' => $request->country_id,
            'phone' => $request->phone,
            'created_by' => Auth::id(),
            'private_note' => $request->private_note,
            'public_note' => $request->public_note,
            'account_manager' => $request->account_manager,
            'account_source_id' => $request->account_source_id,
            'website' => $request->website,
            'industry_id' => $request->industry_id,
            'currency_id' => $request->currency_id,
        ]);

        $account->load(['category', 'country', 'state', 'industry', 'manager', 'currency', 'payment_term', 'source',
        'contacts', 'quotes', 'opportunities', 'invoices']);
        
        return response()->json(['account' => $account], 200);
    }




    /**
     * @OA\Delete(
     *      path="/api/app/crm/accounts/{id}",
     *      operationId="deleteAccountById",
     *      tags={"CRM Account"},
     *      summary="Delete customer account",
     *      description="Returns list of account",
     *      @OA\Parameter(
     *          name="id",
     *          description="Account id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *     )
     *
     */
    public function delete(Request $request, $id)
    {
        
        $account = Account::find($id);
        
        if (!$account){
        
            $response = (new ApiResponse)->error('Customer account does not exist');
            return response()->json($response, 402);
        }
                    
        $account = Account::destroy($id);
        
        $response = (new ApiResponse)->success(['account' => $account]);
                
        return response()->json($response, 200);
    }
}
