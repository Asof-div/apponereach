<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\Billing;
use App\Models\PaymentTransaction as Payment;
use Carbon\Carbon;
use App\Events\SubscriptionReminder;
use App\Events\RenewSubscription;

use App\Services\SubscriberMailService;
use Auth;

class SubscriberService{

    public function __construct(SubscriberMailService $subscriber){
        
    	$this->subscriber = $subscriber;
	}
    
    public function suspendAccount(&$tenant){

        $today = Carbon::today();        
        $tenant->load(['subscriptions' => function($query) use ($today){
            $query->whereDate('start_time', '<=', $today )->whereDate('end_time', '>=', $today );
        }]);
        
        $subscription = $tenant->subscriptions->first();
        if($subscription){
        	if(strtolower($subscription->status) == 'pending'){
	        
	            $tenant->status = 'Suspended';

				$this->changeCurrentSub($tenant, $subscription);

		    	$tenant->save();

	        }

        }
    
    }

    public function startProcessingSubscription(&$tenant){
        $today = Carbon::today();
        $tenant->load(['subscriptions' => function($query) use ($today){
            $query->whereDate('start_time', '<=', $today )->whereDate('end_time', '>=', $today );
        }]);
        $subscription = $tenant->subscriptions->first();
        if($subscription){
            if($tenant->auto_rebill){

            	$this->activateAccount($tenant, $subscription);
               
            }else{

                if(strtolower($subscription->status) == 'processing' ){
	            	
	            	$this->activateAccount($tenant, $subscription);
                    
                }elseif (strtolower($subscription->status) == 'pending') {
                    
                	$this->expireAccount($tenant, $subsciption);
                }
                
            }
        }

    }

    public function expireAccount(&$tenant, $subsciption){
    	
    	$tenant->status = 'Expired';

		$this->changeCurrentSub($tenant, $subscription);

    	$tenant->save();
    
    }

    public function activateAccount(&$tenant, &$subscription){

		$tenant->expiration_date = $subscription->end_time;
		$tenant->status = 'Activated';

		$this->changeCurrentSub($tenant, $subscription);

    	$tenant->save();

        if( strtolower($subscription->status) != 'success'){

	        $subscription->update([
	            'status' => 'success'
	        ]);

	        $billing = $subscription->billings->where('billing_type', 'renewal')->where('status', 'pending')->orWhere('status', 'processing')->first();

            if($billing){
                $billing->update(['status' => 'success']);
            }

	    }

        return true;

    }

    public function changeCurrentSub(&$tenant, $subscription){
    	if($tenant->current_subscription != $subscription->id ){
    		$tenant->last_subscription = $tenant->current_subscription;
    		$tenant->current_subscription = $subscription->id;
    		
    		$lastsubscription = $tenant->lastsubscription;
    		if($lastsubscription){

    			if(strtolower($lastsubscription->status) == 'pending' ){
    				$lastsubscription->update(['status' => 'cancel']);
    				foreach ($lastsubscription->billings->where('status', '<>', 'success') as $billing) {
    					$billing->update(['status' => 'cancel']);
    				}
    			}

    		}
    	}
    }



    public function createNextSubscription($tenant, $end_time){
        $start = $end_time->copy()->addDay()->startOfDay();
        $end = $end_time->copy()->addMonth()->endOfDay();

        $tenant->load(['subscriptions' => function($query) use ($end){
            $query->whereDate('start_time', '<=', $end )->whereDate('end_time', '>=', $end->copy()->subHour() );
        }]);

        if(count($tenant->subscriptions) == 0){
            $subscription = $tenant->subscription;
            if($subscription){
                $newsubscription = $subscription->replicate(); 
                $newsubscription->payment_status = 'unpaid';
                $newsubscription->start_time = $start;
                $newsubscription->end_time = $end;
                $newsubscription->duration = $end->diffInDays($start);
                $newsubscription->status = 'pending';
                $newsubscription->description = "Renewal";
                $newsubscription->push();
                $this->generateOrder($newsubscription);
            }


        }

    }



    private function generateOrder(&$subscription){

        $proration = $this->prorator($subscription->start_time, $subscription->end_time, $subscription->package->price);
        $msisdn = 0;
        if( isset(json_decode($subscription->extra_msisdn, true)['items']) ){
            $msisdn = (int) json_decode($subscription->extra_msisdn, true)['items'];
        }

        $ordered_items = [
                ['product' => strtoupper($subscription->package->name) , 'type' => 'PLAN', 'items' => 1, 'period' => $proration['days'] .' Days', 'amount' => $proration['amount'], 'charged' => $proration['charged'], 'status' => 1 ],
                ['product' => 'Local DID Number', 'type' => 'DID', 'items' => 1, 'period' => ' Service Period', 'amount' => 500 ,'charged' => 0, 'status' => 1, 'description' => 'Pilot Number - '.$subscription->pilot_line ]
                
            ];

        if( $msisdn > 0){
            $amount = $msisdn * 500;
            $ordered_items[] = ['product' => 'MSISDN' , 'type' => 'Addons', 'items' => $msisdn, 'description' => 'Purchase Extra MSISDN Slot 500' , 'period' => '1 Month', 'amount' => $amount, 'charged' => $amount, 'status' => 1 ];
        }
        $charged = 0;
        $total = 0;
        foreach ($ordered_items as $item) {
            $charged = $charged + $item['charged'];
            $total = $total + $item['amount'];
        }
        $subscription->update(['amount' => $proration['charged'], 'total' => $charged]);

        $billing = Billing::create([
            'tenant_id' => $subscription->tenant_id,
            'email' => $subscription->tenant->info->email,
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
            'ordered_items' => json_encode($ordered_items),
    
            ]);

    }

    public function generatePaymentStatement($tenant, &$subscription){

        if(strtolower($subscription->billing_method) == 'postpaid' && strtolower($subscription->payment_status) == 'unpaid' && strtolower($subscription->status) == 'success'){

            $payment = new Payment;
            $today = Carbon::today();

            $orders = $subscription->load(['billings' => function($query) {
                $query->where('status', 'success')->where('payment_status', 'unpaid')->where('payment_transaction_id', null);
            }])->billings;
    
            $payment_no = $today->format('Y-m-d_').$tenant->code.sprintf("-%06X", $this->generate());

            if( count($orders) > 0 ){
                $amount = 0;
                foreach ($orders as $order) {
                    $amount = $amount + (float) $order->charged;
                }

                $payment->tenant_id = $subscription->tenant_id;
                $payment->subscription_id = $subscription->id;
                $payment->transaction_method = $subscription->billing_method;
                $payment->email = $subscription->tenant->info->email;
                $payment->firdtname = $subscription->tenant->info->firdtname;
                $payment->lastname = $subscription->tenant->info->lastname;
                $payment->transaction_type = 'invoice';
                $payment->transaction_no = $payment_no;
                $payment->currency = $subscription->currency;
                $payment->payment_method = 'invoice';
                $payment->payment_channel = 'invoice';
                $payment->status = 'processing';
                $payment->generated_by = 'system';
                $payment->amount = (float) $amount;
                $payment->email = Auth::user()->email;
                $payment->firstname = Auth::user()->firstname;
                $payment->lastname = Auth::user()->lastname;
                $payment->save();

                foreach ($orders as $order) {
                    $order->update([
                        'payment_transaction_id' => $payment->id,
                        'payment_status' => 'processing',
                    ]);
                }

                $subscription->update([
                    'payment_status' => 'processing',
                ]);

            }

        }

    }

    private function prorator($start, $end, $amount){

        $now = $start;

        $days = (int) $end->diffInDays($now);
        $days_in_a_month = $end->diffInDays($now->copy()->startOfMonth());

        $amount = round( $amount, 2);
        $charged_per_day = $amount / $days_in_a_month;
        $charged = round( $days * $charged_per_day, 2);

        return ['days' => $days , 'charged' => $charged, 'amount' => $amount];

    }

    public function generate(){
        $todays_payment = Payment::whereDate('created_at', Carbon::now()->format('Y-m-d').'%' )->get();
        $id = 1;
        if($todays_payment){
            $id = count($todays_payment) + 1;
        }

        return $id;
    }



    public function sendNotification($type, $tenant, $days=0){

    	switch (strtolower($type)) {
    		case 'reminder':
    			$this->subscriber->preNotication($tenant, $days);
    			break;
    		case 'renewal':
    			$this->subscriber->renewalNotication($tenant, $days);
    			break;
    		case 'grace':
    			$this->subscriber->postNotication($tenant, $days);
    			break;
    		case 'suspension':
    			$this->subscriber->suspensionNotication($tenant);
    			break;

    		default:

    			break;
    	}
    }


}