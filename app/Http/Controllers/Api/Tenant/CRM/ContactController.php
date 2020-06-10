<?php

namespace App\Http\Controllers\Api\Tenant\CRM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Contact;

use Illuminate\Validation\Rule;

use App\Services\Response\ApiResponse;
use App\Traits\GetUserResourceTrait;

use Auth;
use Validator;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $contacts = Contact::company($request->user()->tenant_id)->with(['account'])->get();
        
        return response()->json(['contacts' => $contacts], 200);
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
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [

            'name' => 'required|min:2|max:255',
            'account_id' => 'required|exists:accounts,id',
            'phone' => 'required_without_all:email',
            'number' => ['required_without_all:email', Rule::unique('contacts', 'number')->where(function ($query) use ($request) {      return $query->where('tenant_id', request()->user()->tenant_id )->where('account_id', $request->account_id);
                })->ignore($request->contact_id) ],
            'country_code' => 'required_without_all:email',
            'email' => 'required_without_all:phone',
            'title' => 'required',

        ]);

        $tenant = request()->user()->tenant;
        $user = request()->user();


        if ($validator->fails()){
        
            $response = (new ApiResponse)->error($validator->errors()->all());
            return response()->json($response, 422);
        }
        
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

        return response()->json([ 'contact' => $contact ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $contact = Contact::company($request->user()->tenant_id)->where('id', $id)->with(['account'])->first();

        // $regex = "/(+234)(7083191132)/";
        // $string = '7083191132';
        // preg_match($regex, $string, $match);
        // dd($match);
        if(!$contact){

            $response = (new ApiResponse)->error('contact does not exist');
            return response()->json($response, 403);
        }
     
        return response()->json(['contact' => $contact], 200);
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

            'account_id' => 'required|exists:accounts,id',
            'number' => ['required_without_all:email', Rule::unique('contacts', 'number')->where(function ($query) use ($request) {      return $query->where('tenant_id', request()->user()->tenant_id )->where('account_id', $request->account_id);
                })->ignore($id) ],
            'phone' => 'required_without_all:email',
            'country_code' => 'required_without_all:email',
            'email' => 'required_without_all:phone',
            'title' => 'required',

        ]);
        
        $tenant = request()->user()->tenant;
        $user = request()->user();
        $contact = Contact::find($id);

        if ($validator->fails()){
        
            $response = (new ApiResponse)->error($validator->errors()->all());
            return response()->json($response, 422);
        }

        if(!$contact){
            $response = (new ApiResponse)->error('contact does not exist');
            return response()->json($response, 403);
        }
        
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

        return response()->json([ 'contact' => $contact]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        
        $contact = Contact::find($id);
        
        if(!$contact){
            $response = (new ApiResponse)->error('contact does not exist');
            return response()->json($response, 403);
        }

        $contact->delete();
        $contacts = Contact::company($request->user()->tenant_id)->get();

        return response()->json(['contacts' => $contacts], 200);    
    }
}
