<?php
namespace App\Services\Tenant;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Package;
use App\Models\TenantInfo;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\Operator\PilotNumber;
use App\Models\PilotLine;
use App\Models\Transaction;

use App\Helpers\PlanHelper;
use App\Events\UserWasRegistered;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use Carbon\Carbon;

use DB;

/**
* 
*/
class RegistrationService
{
	
	public function __construct()
	{
        $this->planHelper = new PlanHelper;
	}

	public function registerTenant($data){

        $planHelper = $this->planHelper;
        $account = $this->processDomain($data['corporation_short_name']);
        $planHelper->setPlan($data['plan']);
        $package = $planHelper->getPackage();

        $tenant = Tenant::create([
            'name' => $data['corporation_name'],
            'domain' => $account,
            'tenant_no' => 'TEN'.sprintf("%09d", time()),
            'package_id' => $package->id,
            'billing_method' => 'prepaid',
            'billing_cycle' => $planHelper->getPeriod(),
            'status' => 'registration',
            'auto_rebill' => true,
        ]);
        
        $this->tenant = $tenant;
        $salt = $tenant->tenant_no;

        $user = User::create([
            'firstname' => ucfirst($data['firstname']),
            'lastname' => ucfirst($data['lastname']),
            'tenant_id' => $tenant->id,
            'email' => strtolower($data['email']),
            'manager' => 1,
            'password' => bcrypt($salt . $data['password']),
        ]);

        $tenant->info()->save( new TenantInfo( 
            array(
                'id_no' => $tenant->code, 
                'corporation_name' => $data['corporation_name'], 
                'corporation_short_name' => $data['corporation_short_name'], 
                
                'email' => strtolower($data['email']), 
                'firstname' => ucfirst($data['firstname']),
                'lastname' => ucfirst($data['lastname']) ,

                'updated_by' => $user->id, 
                'billing_method' => 'prepaid', 

                )
            ));
        
        $start = Carbon::now();
        $planHelper->setStartDate($start);
        $end = $planHelper->getEndDate();

        $subscription = Subscription::create([
            'tenant_id' => $tenant->id,
            'package_id' => $package->id,
            'duration' => $planHelper->getDuration(),
            'billing_method' => 'prepaid',
            'start_time' => $start,
            'end_time' => $end,
            'expiry_date' => $start->copy()->addHours(6),
            'amount' => $planHelper->getPrice(),
            'total' => (float) $planHelper->getPrice(),
            'cycle' => $planHelper->getPeriod(),
            'currency' => $planHelper->getCurrency(),
            'addons' => json_encode([]),
            'extra_msisdn' => json_encode([]),
            'description' => implode(", ", $package->addons->pluck('label')->toArray()),
            'manager_id' => $user->id,
            'payment_status' => 'unpaid',
            'status' => 'processing',
            
            ]);

        $order = $subscription->generateCart();

        OrderItem::create([
            'tenant_id' => $tenant->id,
            'order_id' => $order->id,
            'quantity' => 1,
            'amount' => $planHelper->getPrice(),
            'charged' => $planHelper->getPrice(),
            'currency' => $planHelper->getCurrency(),
            'description' => $planHelper->getDesc(),
            'type' => 'plan',
            'name' => strtoupper($package->name),
            'item' => $planHelper->getPeriod(),
            ]);
        $order->update([
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,   
            'amount' => $planHelper->getPrice(),
            'charged' => $planHelper->getPrice(),
        ]);
        $tenant->update(['current_subscription' => $subscription->id]);

        return $user;

	}


	public function activateTenant(Transaction $payment, User $user){

        $planHelper = $this->planHelper;
		
        $order = $payment->order;
                            
        $order->update(['status' => 'success', 'payment_status' => true]);
        
        
        $now = Carbon::now();
        $subscription = $payment->subscription;
        $tenant = $payment->tenant;

        $planHelper->setPackage($subscription->package);
        $planHelper->setPeriod($subscription->cycle);
        $start = Carbon::now();
        $planHelper->setStartDate($start);

        $end = $planHelper->getEndDate();
        $subscription->update([
                    'status' => 'success',
                    'start_time' => $start,
                    'end_time' => $end
                ]);


        // Update Tenant Subscription

        $tenant->update([
                'status' => 'activated',
                'expired' => 0,
                'expiration_date' => $end,
                'activated' => 1,
                'stage' => 'active',
            ]);
        $tenant->info->update(['activation_date' => Carbon::now()]);

        $pilot_numbers = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->where('available',0)->orderBy('release_time', 'desc')->get();  

        foreach ($pilot_numbers as $key => $pilot) {

            $pilot->update(['status' => 'allocated', 'purchased' => 1, 'available' => 0]);

            PilotLine::create([
                'tenant_id' => $pilot->tenant_id,
                'number' => $pilot->number,
                'label' => $pilot->label,
                'type_id' => $pilot->type_id,

            ]);
        }
        
        event(new UserWasRegistered($user, ''));

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