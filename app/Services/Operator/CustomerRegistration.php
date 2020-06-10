<?php
namespace App\Services\Operator;

use Exception;

use App\Models\Tenant;
use App\Models\TenantInfo;
use App\Models\Package;
use App\Models\UserReg;
use App\Models\PostPaid;
use App\Models\Billing;
use App\Models\Subscription;
use App\Models\PaymentTransaction;
use App\Models\Operator\PilotNumber;
use App\Models\PilotLine;

use Carbon\Carbon;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use App\Events\UserWasRegistered;
use App\Events\AppError;

use Auth;
use DB;
/**
* 
*/
class CustomerRegistration 
{
	
	function __construct()
	{
		# code...
		$this->days_in_a_month = 30;
		$this->grace_period = 3;
	}

	public function register($data){

		try{
			DB::beginTransaction();

			$account = $this->processDomain($data['corporation_short_name']);
			$formPackage = isset($data['package']) ? $data['package'] : '';
	        $package = Package::find($formPackage);
	        $package = $package ? $package : Package::where('name', 'Free')->get()->first();

	        $tenant = Tenant::create([
	            'name' => $data['corporation_name'],
	            'domain' => $account,
	            'tenant_no' => 'TEN'.sprintf("%09d", time()),
	            'package_id' => $package->id,
	            'billing_method' => 'postpaid',
	            'billing_cycle' => 'Monthly',
	            'status' => 'registration',
	            'auto_rebill' => true,
	        ]);
	        
	        $this->tenant = $tenant;
	        $proration = $this->prorator($package->price);

	        $this->salt = $tenant->code.'_';
	        $this->password = $tenant->code;


	        $user = UserReg::create([
	            'firstname' => ucfirst($data['firstname']),
	            'lastname' => ucfirst($data['lastname']),
        		'middlename' => ucfirst($data['middlename']),
	            'tenant_id' => $tenant->id,
	            'email' => strtolower($data['email']),
	            'manager' => 1,
	            'password' => bcrypt($this->salt . $this->password),
	        ]);

	        $tenant->info()->save( new TenantInfo( 
	        	array(
	        		'id_type' => $data['id_type'], 
	        		'id_no' => $tenant->code, 
	        		'corporation_name' => $data['corporation_name'], 
	        		'corporation_type' => $data['corporation_type'], 
	        		'corporation_short_name' => $data['corporation_short_name'], 
	        		'customer_category' => $data['customer_category'], 
	        		'customer_type' => $data['customer_type'], 
	        		'customer_grade' => $data['customer_grade'], 
	        		'customer_sub_category' => $data['customer_sub_category'], 
	        		'industry' => $data['industry'], 
	        		'registration_date' => $data['register_date'], 
	        		'size_level' => $data['size_level'], 
	        		'sub_industry' => $data['sub_industry'], 
	        		'language' => $data['language'], 
	        		'register_capital' => $data['register_capital'], 
	        		'email' => strtolower($data['email']), 
	        		'firstname' => ucfirst($data['firstname']),
	        		'lastname' => ucfirst($data['lastname']) ,
	        		'middlename' => ucfirst($data['middlename']),
	        		'title' => $data['title'], 
	        		'address' => $data['address'], 
	        		'state' => $data['state'], 
	        		'nationality' => $data['nationality'], 
	        		'updated_by' => $user->id, 
	        		'billing_method' => 'postpaid', 
	        		'home_no' => isset($data['home_no']) ? $data['home_no'] : '',
	        		'mobile_no' => isset($data['mobile_no']) ? $data['mobile_no'] : '',
	        		'office_no' => isset($data['office_no']) ? $data['office_no'] : '',
	        		'fax_no' => isset($data['fax_no']) ? $data['fax_no'] : '',

	        		)
	        	));
			
		    $start = Carbon::now();
	        $end = $start->copy()->endOfMonth();
	        $due = $start->copy()->addHours(3);

	        $ordered_items = [
	        	['product' => strtoupper($package->name) , 'type' => 'PLAN', 'items' => 1, 'period' => $proration['days'] .' Days', 'amount' => $proration['amount'], 'charged' => $proration['charged'], 'status' => 1 ],
	        	['product' => 'Local DID Number', 'type' => 'DID', 'items' => 1, 'period' => ' Service Period', 'amount' => 500 ,'charged' => 0, 'status' => 0 ],
	        ];


	        $subscription = Subscription::create([
	            'tenant_id' => $tenant->id,
	            'package_id' => $package->id,
	            'duration' => $proration['days'],
	            'billing_method' => 'postpaid',
	            'isPostPaid' => 1,
	            'start_time' => $start,
	            'end_time' => $end,
	            'amount' => (float) $proration['charged'],
	            'total' => (float) $proration['charged'],
	            'cycle' => 'Monthly',
	            'currency' => '&#x20A6;',
	            'addons' => json_encode([]),
	            'extra_msisdn' => json_encode([]),
	            'description' => "Sign Up : " .$package->name,
				'manager_id' => $data['issued_by'],
	            'payment_status' => 'unpaid',
	            'status' => 'processing',
	            
	            ]);

	        $billing = Billing::create([
	            'tenant_id' => $tenant->id,
	            'email' => strtolower($data['email']),
	            'firstname' => ucfirst($data['firstname']),
	            'lastname' => ucfirst($data['lastname']),
	            'billing_method' => $tenant->billing_method,
	            'subscription_id' => $subscription->id,
	            'ordered_date' => $start,
	            'due_date' => $due,
	            'payment_method' => $data['payment_method'],
	            'amount' => (float) $proration['amount'] + 500,
	            'charged' => (float) $proration['charged'],
	            'currency' => '&#x20A6;',
	            'billing_type' => 'registration',
	            'status' => 'processing',
	            'payment_status' => 'unpaid',
	            'description' => "Sign Up  : " .$package->name,
	            'ordered_items' => json_encode($ordered_items),
				'issuer_type' => 'operator',
            	'issuer_name' => Auth::user()->name,
            	'issuer_email' => Auth::user()->email,
	            ]);


	        $tenant->update(['current_subscription' => $subscription->id]);

	        DB::commit();
	        return ['status' => ['success' => 'Customer Account Successfully Created', 'url' => route('operator.customer.registration', [$tenant->id]) ], 'code' => 200];
	    }catch(Exception $e){
	    	DB::rollback();
	        return ['status' => ['error' => ['Unable to register customer info']], 'code' => 422];
	    }

	}


	public function activate($data){

		try{
			DB::beginTransaction();
			$tenant = Tenant::find($data['customer_id']);

			if( $tenant->activated == 0 && strtolower($tenant->billing_method) == 'postpaid' &&  $tenant->pilot_lines->count() <= 0){

				$billing = Billing::find($data['billing']);
		   		$pilot = PilotNumber::find($data['pilot_number']);

  		
		   		$subscription = Subscription::find($data['subscription']);

		   		$start = Carbon::now();

		        $subscription->update([
		        		'status' => 'success',
		        		'pilot_line' => $pilot->number,
		        		'end_time' => $start->copy()->endOfMonth()
		        	]);

		   
		   		
		   		// Update Tenant Subscription

		   		$tenant->update([
			   			'status' => 'activated',
			   			'expired' => 0,
			   			'expiration_date' => $start->copy()->endOfMonth(),
			   			'activated' => 1,
		   			]);
		   		$tenant->info->update(['activation_date' => Carbon::now()]);
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

		   		$pilot->update(['status' => 'allocated', 'billing_id' => $data['billing'], 'purchased' => 1, 'available' => 0]);

		    	PilotLine::create([
		            'tenant_id' => $pilot->tenant_id,
		            'caller_id_name' => $tenant->name,
					'number' => $pilot->number,
					'voicemail_email' => $user->email,
		            'type' => $pilot->type,
				]);

				$items = [];
				foreach( json_decode($billing->ordered_items, true) as $item ){
					if($item['status'] == 0 && $item['type'] == 'DID'){
						$item['status'] = 1;
						$item['description'] = 'Pilot Number - '. $pilot->number;
					}elseif($item['status'] == 0){
						$item['status'] = 1;
					}
					$items[] = $item;
				}

	            $billing->update([ 'status' => 'success', 'ordered_items' => json_encode($items),]);

		        event(new UserWasRegistered($user, $password));
		    	DB::commit();

		        return ['status' => ['success' => 'Customer Account Successfully Activated', 'url' => route('operator.customer.show', [$tenant->id])], 'code' => 200 ];
			}else{

		        return ['status' => ['error' => ['Unable to activate customer account']], 'code' => 422];
			}
	    
	    }catch(Exception $e){

	    	DB::rollback();
	        // event(new AppError('Operator', 'Services/CustomerRegistration.php', 'Activate', Auth::user()->email, $e->getMessage() ));
	        return ['status' => ['error' => ['Unable to update customer info']], 'code' => 422];

	    }
	}


	public function update($data){

		try{
			DB::beginTransaction();

	        $tenant = Tenant::find($data['customer_id']);

	        $tenant->update([
	            'name' => $data['corporation_name'],
	            'package_id' => $data['package'],
	            'billing_method' => 'postpaid',
	            'billing_cycle' => 'Monthly',
	            'auto_rebill' => true,
	        ]);
	        $info = $tenant->info;

	        $info->update( 
	        	array(
	        		'id_type' => $data['id_type'], 
	        		'id_no' => $tenant->code, 
	        		'corporation_name' => $data['corporation_name'], 
	        		'corporation_type' => $data['corporation_type'], 
	        		'customer_type' => $data['customer_type'], 
	        		'customer_category' => $data['customer_category'], 
	        		'customer_grade' => $data['customer_grade'], 
	        		'customer_sub_category' => $data['customer_sub_category'], 
	        		'industry' => $data['industry'], 
	        		'size_level' => $data['size_level'], 
	        		'sub_industry' => $data['sub_industry'], 
	        		'language' => $data['language'], 
	        		'register_capital' => $data['register_capital'], 
	        		'email' => strtolower($data['email']), 
	        		'firstname' => ucfirst($data['firstname']),
	        		'lastname' => ucfirst($data['lastname']) ,
	        		'middlename' => ucfirst($data['middlename']),
	        		'title' => $data['title'], 
	        		'address' => $data['address'], 
	        		'state' => $data['state'], 
	        		'nationality' => $data['nationality'],
	        		'billing_method' => 'postpaid', 
	        		'home_no' => isset($data['home_no']) ? $data['home_no'] : '',
	        		'mobile_no' => isset($data['mobile_no']) ? $data['mobile_no'] : '',
	        		'office_no' => isset($data['office_no']) ? $data['office_no'] : '',
	        		'fax_no' => isset($data['fax_no']) ? $data['fax_no'] : '',

	        		)
	        	);
			
	        DB::commit();
	        return ['status' => ['success' => 'Customer Account Successfully Created', 'url' => route('operator.customer.show', [$tenant->id])], 'code' => 200 ];
	    }catch(Exception $e){
	    	DB::rollback();
	    	\Log::log('info', $e->getMessage());
	        return ['status' => ['error' => ['Unable to update customer info']], 'code' => 422];

	    }

	}

	public function suspend($data){

		try{

	        $tenant = Tenant::find($data['customer_id']);
	        if($tenant &&  $tenant->subscription){

		        $subscription = $tenant->subscription;
	        	$unpaids = $subscription->where('payment_status', '<>', 'paid')->where('payment_status', '<>', 'success');
				if( count($unpaids) > 0 ){		        
			        $tenant->update([
			            'status' => 'Suspended',
			        ]);
			        
			        return ['status' => ['success' => 'Customer Account Successfully Suspended', 'url' => route('operator.customer.show', [$tenant->id])], 'code' => 200 ];
			    }
			}

	        return ['status' => ['error' => ['Customer Account Cannot Be Suspended, because there are no unpaid order.'], 'url' => route('operator.customer.show', [$tenant->id])], 'code' => 200 ];
	    }catch(Exception $e){
	        return ['status' => ['error' => ['Unable to suspend customer account']], 'code' => 422];

	    }
		
	}

	public function renew($data)
    {
        try{

	        $tenant = Tenant::find($data['customer_id']);
	        if($tenant &&  $tenant->subscription){

		        $tenant->update([
		            'status' => 'Activated',
		            'auto_rebill' => true,
		        ]);
		        
		        return ['status' => ['success' => 'Customer Account Successfully Activated', 'url' => route('operator.customer.show', [$tenant->id])], 'code' => 200 ];
		    
			}

	        return ['status' => ['error' => ['Customer Account Cannot Be Activated'], 'url' => route('operator.customer.show', [$tenant->id])], 'code' => 200 ];
	    }catch(Exception $e){
	    	\Log::log('info', $e->getMessage());
	        return ['status' => ['error' => ['Unable to activate customer account']], 'code' => 422];

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

    private function getDuration($cycle){
    	$cycle = strtolower($cycle);
    	$duration =  1;
    	switch ($cycle) {
    		case 'monthly':
    			$duration = 1;
    			break;
    		case 'quarterly':
    			$duration = 3;
    			break;
    		case 'yearly':
    			$duration = 12;
    			break;
    		default:
    			$duration = 1;
    			break;
    	}

    	return $duration;

    }

    private function prorator($amount){

    	$now = Carbon::now();
    	$end = $now->copy()->endOfMonth()->addDay();

    	$days =  (int) $end->diffInDays($now);
    	$days_in_a_month = $end->diffInDays($now->copy()->startOfMonth());

    	$amount = round( $amount, 2);
    	$charged_per_day = $amount / $days_in_a_month;
    	$charged = round( $days * $charged_per_day, 2);

    	return ['days' => $days , 'charged' => $charged, 'amount' => $amount];

    }


}