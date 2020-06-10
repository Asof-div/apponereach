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

class SubscriberMailService{


    public function preNotification($tenant, $days){
        $today = Carbon::today();

        $subscription = $tenant->subscription->whereDate('end_time', '=',  $today->copy()->addDay($days)->format('Y-m-d').'%')->get()->first();

        if($subscription){
            event(new SubscriptionReminder($subscription));    
        }

    }

    public function postNofication($tenant, $day){


    }

    public function renewalNotification($tenant){

    }

    public function suspensionNotification($tenant){
        
    }


}