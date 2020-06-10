<?php
namespace App\Services;

use App\Models\UserReg;
use App\Models\Tenant;
use App\Models\Package;
use App\Models\TenantInfo;
use App\Models\Cart;
use App\Models\Billing;
use App\Models\Subscription;
use App\Models\Operator\PilotNumber;
use App\Models\PilotLine;

use App\Events\UserWasRegistered;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use Carbon\Carbon;

use App\Services\PaymentGateway\PayStackBillingService as PayStack;

/**
* 
*/
class RegistrationService
{
	
	public function __construct()
	{
		$this->payStack = new PayStack;
	}

	public function registerTenant($data){


		$account = $this->processDomain($data['domain_name']);
		$formPackage = substr( $data['package'], 2);
		$formPeriod = substr( $data['package'], 0, 2);
        $package = Package::where('name', $formPackage)->get()->first();
        $package = $package ? $package : Package::where('name', 'Free')->get()->first();

        $tenant = Tenant::create([
            'name' => $data['company_name'],
            'domain' => $account,
            'tenant_no' => 'TEN'.sprintf("%09d", time()),
            'package_id' => $package->id,
            'billing_method' => 'prepaid',
            'status' => 0,
        ]);
        
        TenantManager::set($tenant);

        $this->salt = $tenant->code.'_';
        $this->password = $tenant->code;

        $user = UserReg::create([
            'firstname' => ucfirst($data['firstname']),
            'lastname' => ucfirst($data['lastname']),
            'tenant_id' => $tenant->id,
            'email' => strtolower($data['email']),
            'manager' => 1,
            'password' => bcrypt($this->salt . $this->password),
        ]);

        $tenant->info()->save( new TenantInfo( array('email' => strtolower($data['email']), 'code' => $data['code'], 'contact_person' => ucwords($data['firstname'].' '.$data['lastname']) ,'telephone' => $data['telephone'], 'street_1' => $data['street_address1'], 'street_2' => $data['street_address2'], 'city' => $data['city'], 'state' => $data['state'], 'country' => $data['country'], 'user_limit' => $data['number_of_users'],'updated_by' => $user->id )));
        $tenant->cart()->save( new Cart() );
        $start = Carbon::now();
        $end = $start->copy();
        $due = $start->copy();
        $end->addDays((int) $formPeriod * 30);
        $due->addDays(7);


        $ordered_items = [
            ['product' => strtoupper($package->name) , 'type' => 'SUBSCRIPTION', 'items' => 1, 'description' => 'Sign up ', 'period' => $formPeriod .' Month', 'amount' => $formPeriod * $package->price, 'charged' => $formPeriod * $package->price, 'status' => 1 ],
            ['product' => 'Local DID Number', 'type' => 'DID', 'items' => 1, 'description' => 'Pilot Number', 'period' => ' Service Period', 'amount' => 500 ,'charged' => 0, 'status' => 0 ],
        ];

        $billing = Billing::create([
            'tenant_id' => $tenant->id,
            'email' => strtolower($data['email']),
            'firstname' => ucfirst($data['firstname']),
            'lastname' => ucfirst($data['lastname']),
            'due_date' => $due,
            'payment_method' => 'Online Payment',
            'amount' => (float) $formPeriod * $package->price ,
            'currency' => 'NGN',
            'billing_type' => 'Registration',
            'status' => 'Processing',
            'description' => "Sign Up Billing \n Package: " .$package->name,
            'ordered_items' => json_encode($ordered_items),
            ]);


    
        $subscription = Subscription::create([
            'tenant_id' => $tenant->id,
            'package_id' => $package->id,
            'billing_id' => $billing->id,
            'duration' => $formPeriod * 30,
            'start_time' => $start,
            'end_time' => $end,
            'amount' => (float) $formPeriod * $package->price,
            'currency' => 'NGN',
            'addon_bits' => 0,
            'extra_msisdn' => 0,
            'description' => "Sign Up Billing \n Package: " .$package->name,
            ]);

        $tenant->update(['current_subscription' => $subscription->id]);

        return $user;
	}


	public function activeTenant($data){

		$result = $this->payStack->verifyPayment($data);

		if(isset($result['status']) && $result['status'] == 'success'){
        
            $billing = Billing::find($data['billing']);
	   	
	   		// Set Subscription Period

	   		$subscription = Subscription::find($data['subscription']);

	   		$start = Carbon::now();
	        $end = $start->copy();
	        $due = $start->copy();
	        $end->addDays((int) $subscription->duration );
	        $subscription->update([
	        		'start_time' => $start,
	        		'end_time' => $end,
	        		'activated' => 1,
	        	]);


	   		// Update Tenant Subscription

	   		$tenant = Tenant::find($data['tenant']);
	   		$tenant->update([
		   			'status' => 1,
		   			'expired' => 0,
		   			'expiration_date' => $end,
	   			]);
	   		$salt = $tenant->tenant_no;

	   		// Update User Account 
	   		$user = $tenant->users->where('manager', 1)->first();
	        $password = chr(rand(48,122)).chr(rand(48,122)).chr(rand(48,122)).chr(rand(48,122)).chr(rand(48,122)).chr(rand(48,122));

	        \Log::log('info', $password);
	   		if($user){
	   			$user->update([
	   				'password' => bcrypt($salt. $password),
	   				'isActive' => 1,
	   				]);
	   			$user = UserReg::find($user->id);
	   		}
	   		// Send Email to user 

	   		// Register Pilot lines  

            $pilot = PilotNumber::find($data['pilot_number']);
            $pilot->update(['status' => 'allocated', 'billing_id' => $data['billing'], 'purchased' => 1, 'available' => 0]);

            PilotLine::create([
                'tenant_id' => $pilot->tenant_id,
                'caller_id_name' => $tenant->name,
                'number' => $pilot->number,
                'type' => $pilot->type,
            ]);

            $items = [];
            foreach( json_decode($billing->ordered_items, true) as $item ){
                if($item['status'] == 0 && $item['type'] == 'DID'){
                    $item['status'] = 1;
                    $item['description'] = 'Pilot Number - '. $pilot->number;
                }
                $items[] = $item;
            }

            $billing->update([ 'status' => 'Success', 'ordered_items' => json_encode($items), 'paid_date' => Carbon::now(),]);



            event(new UserWasRegistered($user, $password));

    		return $result;


		}else if(isset($result['status']) && $result['status'] == 'error'){

			
			return $result;

		}else{


			return  array_add($result, 'status', 'error');

		}

	}

    private function processDomain($domain){
        $domain = strtolower($domain);
        $domain = str_replace(" ", "_", $domain);
        $domain = str_replace("/", "_", $domain);
        $domain = str_replace("\\", "_", $domain);
        $domain = str_replace("/", "_", $domain);

        return $domain;
    }

}