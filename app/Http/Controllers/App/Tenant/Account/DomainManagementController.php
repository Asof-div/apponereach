<?php

namespace App\Http\Controllers\App\Tenant\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use App\Models\Tenant;
use Auth;
use Validator;
use File;

class DomainManagementController extends Controller
{
    
    function __construct(){
    	$this->middleware(['auth:web', 'tenant']);
    }

	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function index(){
		

    	return view('app.tenant.account.domain.index');
    }


    function setting($tenant, Request $request){

    	$validator = Validator::make($request->all(), [

            'id_type' => 'required|max:250',
            // 'customer_category' => 'required|max:250',
            // 'customer_type' => 'required|max:250',
            // 'corporation_name' => 'required|max:250',
            // 'corporation_type' => 'required|max:250',
            // 'corporation_short_name' => 'required|unique:tenants,domain|max:250',
            // 'industry' => 'required|max:250',
            // 'language' => 'required|max:250',
            // 'customer_sub_category' => 'required|max:250',
            // 'customer_grade' => 'required|max:250',
            'nationality' => 'required|max:250',
            'email' => 'required|max:250|email',
            'firstname' => 'required|max:250',
            'lastname' => 'required|max:250',
            'middlename' => 'required|max:250',
            'title' => 'required|max:250',
            'address' => 'required|max:250',
            'state' => 'required|max:250',
            'home_no' => 'required_without_all:mobile_no,office_no,fax_no|max:250',
            'mobile_no' => 'required_without_all:home_no,office_no,fax_no|max:250',
            'office_no' => 'required_without_all:home_no,mobile_no,fax_no|max:250',
            'fax_no' => 'required_without_all:home_no,office_no,mobile_no|max:250',

        ]);


        if ($validator->passes()) {

        	if($request->ajax()){
    		
    		
	    		$tenant = TenantManager::get();  		
	    		$tenant->info->update([
	    			'email' => $request->email,
	    			'firstname' => $request->firstname,
	    			'lastname' => $request->lastname,
	    			'middlename' => $request->middlename,
	    			'home_no' => $request->home_no,
	    			'mobile_no' => $request->mobile_no,
	    			'office_no' => $request->office_no,
	    			'fax_no' => $request->fax_no,
	    			'title' => $request->title,
	    			'nationality' => $request->nationality,
	    			'address' => $request->address,
	    			'state' => $request->state,
	    			// 'customer_category' => $request->customer_category,
	    			'language' => $request->language,
	    			'id_type' => $request->id_type,


	    			]);
		    	$tenant->update();

	    	}



			return response()->json(['success'=>'Business Information Successfully Updated.', 'tenant' => $tenant, 'official' => json_encode($tenant->info->official()) ]);

        }


    	return response()->json(['error'=>$validator->errors()->all()], 422);

    	

    }


    function logo($tenant, Request $request){

    	$validator = Validator::make($request->all(), [

            'logo' => 'required|image|max:2000',

        ]);


        if ($validator->passes()) {

        	if($request->ajax()){
    		
    		
	    		$tenant = TenantManager::get();
	    		
	    		if($request->hasFile('logo') ){

					$logo = $request->file('logo');
					
		
					$image_path = public_path()."/".$tenant->info->logo;
				
					if (File::exists($image_path)) {
						File::delete($image_path);
						// unlink($image_path);
					}

		    		$tmpName = "business_logo.". $logo->getClientOriginalExtension(); 
		    		$filePath = 'tenants_logos/'.$tenant->tenant_no ;
		    		$tenant->info->logo = $filePath."/".$tmpName;
		    		$request->file('logo')->move(public_path($filePath) , $tmpName);

		    	}

		    	$tenant->info->updated_by = Auth::user()->id;
		    	$tenant->info->update();
		    	
		    

			return response()->json(['success'=>'Business Logo Successfully Uploaded.']);

			}
        }


    	return response()->json(['error'=>$validator->errors()->all()], 422);

    }


    function deleteLogo($tenant, Request $request){

        $validator = Validator::make($request->all(), [

            'tenant_id' => 'required|exists:tenants,id',

        ]);

        if ($validator->passes()) {
            
            $tenant = Tenant::find($request->tenant_id);

            if($tenant){

                if($tenant->info->logo){

                    exec('rm -r ' .public_path($tenant->info->logo));
                    $tenant->info->update(['logo' => '']);

                    return response()->json(['success'=>'Logo Successfully Deleted.']  );
                }

                return response()->json(['error'=>['Logo is not found.'] ] , 422);
            }

            return response()->json(['error'=>['Unable to delete logo.'] ] , 422);


        }

        return response()->json(['error'=>$validator->errors()->all()], 422);

    }


}
