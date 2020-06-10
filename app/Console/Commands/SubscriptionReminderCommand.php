<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\Billing;
use App\Models\PaymentTransaction as Payment;
use Carbon\Carbon;

use App\Services\SubscriberService;


class SubscriptionReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscription reminder events';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SubscriberService $subscriber)
    {
        parent::__construct();
        $this->subscriber = $subscriber;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::today();
        $subscriber = $this->subscriber;
        
        $tenants = Tenant::where('status', '<>', 'leave')->whereHas('subscriptions', function($query) use ($today){  
            $query->whereDate('start_time', '<=', $today)->whereDate('end_time', '>=', $today); 
        })->get();
    
        foreach ($tenants as $tenant) {
            $days = $today->diffInDays($tenant->subscription->end_time, false);
            if($days == 15 ){
                $subscriber->sendNotification('reminder', $tenant, $days);
            }elseif ($days == 7) {
                $subscriber->sendNotification('reminder', $tenant, $days);
            }elseif ($days == 3) {
                $subscriber->sendNotification('reminder', $tenant, $days);
                $subscriber->createNextSubscription($tenant, $tenant->subscription->end_time);
                $subscriber->generatePaymentStatement($tenant, $tenant->subscription);
            }elseif ($days < 3 && $days >= 0) {

                $subscriber->createNextSubscription($tenant, $tenant->subscription->end_time);                
                $subscriber->generatePaymentStatement($tenant, $tenant->subscription);
            }elseif($days == -1 || $days == -2 || $days < -1){

                $subscriber->startProcessingSubscription($tenant);
            }


            $grace = $today->diffInDays($tenant->subscription->start_time, false);

            if($grace == -1 && strtolower($tenant->status) == 'expired' ){
            
                // Your account will be suppended in hours
                $subscriber->sendNotification('grace', $tenant);

            }elseif($grace == -3 && strtolower($tenant->status) == 'expired' ){
                
                // Your account will be suppended in hours
                $subscriber->sendNotification('grace', $tenant);

            }elseif($grace < -3 && strtolower($tenant->status) == 'expired' ){

                $subscriber->suspendAccount($tenant);                                
                // Your account have been suppended 
                $subscriber->sendNotification('suspension', $tenant);

            }

            \Log::log('info', 'days ' . $days);
            \Log::log('info', 'grace ' . $grace);
            \Log::log('info', 'start date ' . $tenant->subscription->start_time);
            \Log::log('info', 'end date ' . $tenant->subscription->end_time);

        }

    }


}
