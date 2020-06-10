<?php
namespace App\Services;

use App\Models\Tenant;
use App\Models\Package;
use App\Models\TenantInfo;
use App\Models\Billing;
use App\Models\Subscription;
use App\Events\RenewSubscription;

use Carbon\Carbon;
use Exception;

use App\Services\PaymentGateway\PayStackBillingService as PayStack;

/**
* 
*/
class RenewSubscriptionService
{
	
	public function __construct()
	{

	}

	public function update($data){

        try {
            $package_id = $data['package'];
            $tenant_id = $data['tenant_id'];
            $subscription_id = $data['subscription_id'];
            $msisdn = $data['extra_msisdn'] ?? 0;

            $subscription = Subscription::company($tenant_id)->where('id', $subscription_id)->get()->first();
            $billing = $subscription->billings->where('billing_type', 'renewal')->first();
            $package = Package::find($package_id);

            if($billing){
               
                $proration = $this->prorator($subscription->start_time, $subscription->end_time, $package->price);
                $items = $this->generateOrderItems($subscription, $proration, $package, $msisdn);
                $charged = 0;
                $total = 0;

                foreach ($items as $item) {
                    $charged = $charged + $item['charged'];
                    $total = $total + $item['amount'];
                }

                $billing->update([ 'amount' => (float) $total, 'charged' => (float) $charged, 'status' => 'pending', 'ordered_items' => json_encode($items),]);
                $subscription->update([
                    'package_id' => $package_id,
                    'extra_msisdn' => json_encode([
                        'items' =>  $msisdn,
                        'price' => 500 * $msisdn
                    ]),
                    'start_time' => $proration['start'], 
                    'amount' => $proration['charged'], 
                    'total' => $charged
                ]);


            }else{

                $proration = $this->prorator($subscription->start_time, $subscription->end_time, $package->price);
                $items = $this->generateOrderItems($subscription, $proration, $package, $msisdn);
                $charged = 0;
                $total = 0;

                foreach ($items as $item) {
                    $charged = $charged + $item['charged'];
                    $total = $total + $item['amount'];
                }
                
                $subscription->update([
                    'package_id' => $package_id,
                    'extra_msisdn' => json_encode([
                        'items' =>  $msisdn,
                        'price' => 500 * $msisdn
                    ]),
                    'start_time' => $proration['start'], 
                    'amount' => $proration['charged'], 
                    'total' => $charged
                ]);

                $billing = Billing::create([
                    'tenant_id' => $subscription->tenant_id,
                    'email' => $subscription->tenant->info->email,
                    'firstname' => $subscription->tenant->info->firstname,
                    'lastname' => $subscription->tenant->info->lastname,
                    'billing_method' => $subscription->billing_method,
                    'subscription_id' => $subscription->id,
                    'ordered_date' => Carbon::now(),
                    'due_date' => $subscription->start_time->addDay(3),
                    'payment_method' => $subscription->billing_method,
                    'amount' => (float) $total,
                    'charged' => (float) $charged,
                    'currency' => '&#x20A6;',
                    'billing_type' => 'renewal',
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'description' => "Subscription Renewal ",
                    'ordered_items' => json_encode($items),
            
                    ]);
            }

            return ['msg' => ['success' => 'Update Successful'], 'code' => 200];


        } catch (Exception $e) {

            return ['msg' => ['error' => ['Unable to update subscription details']], 'code' => 422];
        }

	}

    public function renew($data){

        try {
            $tenant_id = $data['tenant_id'];
            $subscription_id = $data['subscription_id'];
            
            $subscription = Subscription::company($tenant_id)->where('id', $subscription_id)->get()->first();
            $billing = $subscription->billings->where('billing_type', 'renewal')->first();

            event(new RenewSubscription($subscription));            

            return ['msg' => ['success' => 'Subscription Successfully Renew'], 'code' => 200];

        } catch (Exception $e) {
            \Log::log('info', $e->getMessage());
            return ['msg' => ['error' => ['Unable to renew subscription']], 'code' => 422];
        }
    }


    private function prorator(&$start, $end, $amount){

        $today = Carbon::today();

        $days = $today->diffInDays($start, false);
        if($days >= 1){
            $start = $start;
        }elseif ($days <= -3) {
            $start = $today->subDays(3);
        }
        $days = (int) $end->diffInDays($start);
        $days_in_a_month = $end->diffInDays($start->copy()->startOfMonth());

        $amount = round( $amount, 2);
        $charged_per_day = $amount / $days_in_a_month;
        $charged = round( $days * $charged_per_day, 2);

        return ['days' => $days , 'charged' => $charged, 'amount' => $amount, 'start' => $start];

    }


    private function generateOrderItems($subscription, $proration, $package, $msisdn=0){

        $ordered_items = [
                ['product' => strtoupper($package->name) , 'type' => 'PLAN', 'items' => 1, 'period' => $proration['days'] .' Days', 'amount' => $proration['amount'], 'charged' => $proration['charged'], 'status' => 1 ],
                ['product' => 'Local DID Number', 'type' => 'DID', 'items' => 1, 'period' => ' Service Period', 'amount' => 500 ,'charged' => 0, 'status' =>1, 'description' => 'Pilot Number - '.$subscription->pilot_line ],
                
            ];

        if( $msisdn > 0){
            $amount = $msisdn * 500;
            $ordered_items[] = ['product' => 'MSISDN' , 'type' => 'Addons', 'items' => $msisdn, 'description' => 'Purchase Extra MSISDN Slot 500' , 'period' => '1 Month', 'amount' => $amount, 'charged' => $amount, 'status' => 1 ];
        }
        
        return $ordered_items;
        
    }
}