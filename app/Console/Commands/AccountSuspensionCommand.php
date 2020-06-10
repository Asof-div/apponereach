<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\Subscription;
use Carbon\Carbon;
use App\Events\SubscriptionReminder;

class AccountSuspensionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:suspension';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Suspend account after 3 days grace of waiting for resubscribing ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::today();
        
        $tenants = Tenant::where('status', '<>', 'Leave')->whereHas('subscriptions',function($query) use ($today){
            $query->whereDate('start_time', '<=', $today)->whereDate('end_time', '>=', $today);
        })->get();

        foreach ($tenants as $tenant) {
            $days = $today->diffInDays($tenant->subscription->end_time, false);
            if($days == 3 && strtolower($tenant->status) == 'expired'){

                // Last Grace Reminder Mail
            }elseif ($days >= 4 && strtolower($tenant->status) == 'expired' ) {

                $this->suspendAccount($tenant);                
            }elseif($days == -1 || $days == -2){

                $this->startProcessingSubscription($tenant);
            }

        }

    }


    public function suspendAccount($tenant){
        $today = Carbon::today();
        $tenant->load(['subscriptions' => function($query) use ($today){
            $query->whereDate('start_time', '<=', $today )->whereDate('end_time', '>=', $today );
        }]);
        $subscription =$tenant->subscriptions->first();
        if($subscription){
            if($tenant->auto_rebill){

                $tenant->update([
                    'last_subscription' => $tenant->last_subscription,
                    'current_subscription' => $subscription->id,
                    'expiration_date' => $subscription->end_time,
                    'status' => 'Activated',
                ]);
                $subscription->update([
                    'status' => 'success'
                ]);

            }else{

                $tenant->update([
                    'last_subscription' => $tenant->last_subscription,
                    'current_subscription' => $subscription->id,
                    'status' => 'Supended',
                ]);
            }
        }
    }

    public function startProcessingSubscription($tenant){
        $today = Carbon::today();
        $tenant->load(['subscriptions' => function($query) use ($today){
            $query->whereDate('start_time', '<=', $today )->whereDate('end_time', '>=', $today );
        }]);
        $subscription =$tenant->subscriptions->first();
        if($subscription){
            if($tenant->auto_rebill){

                $tenant->update([
                    'last_subscription' => $tenant->last_subscription,
                    'current_subscription' => $subscription->id,
                    'expiration_date' => $subscription->end_time,
                    'status' => 'Activated',
                ]);
                $subscription->update([
                    'status' => 'success'
                ]);

            }else{

                if(strtolower($subscription->status) == 'processing' ){
                    $tenant->update([
                        'last_subscription' => $tenant->last_subscription,
                        'current_subscription' => $subscription->id,
                        'expiration_date' => $subscription->end_time,
                        'status' => 'Activated',
                    ]);
                    $subscription->update([
                        'status' => 'success'
                    ]);
                }elseif (strtolower($subscription->status) == 'pending') {
                    $tenant->update([
                        'last_subscription' => $tenant->last_subscription,
                        'current_subscription' => $subscription->id,
                        'status' => 'Expired',
                    ]);

                }
                
            }
        }

    }

}
