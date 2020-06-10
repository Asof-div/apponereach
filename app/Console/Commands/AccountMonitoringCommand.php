<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\Subscription;
use Carbon\Carbon;
use App\Events\SubscriptionReminder;

use App\Services\SubscriberService;

class AccountMonitoringCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:monitoring';

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

        $tenants = Tenant::where('status', '<>', 'Leave')->whereHas('subscriptions',function($query) use ($today){
            $query->whereDate('start_time', '<=', $today)->whereDate('end_time', '>=', $today);
        })->get();

        foreach ($tenants as $tenant) {
            $days = $today->diffInDays($tenant->subscription->end_time, false);
            if($days == 3 && strtolower($tenant->status) == 'expired'){

                // Last Grace Reminder Mail
            }elseif ($days >= 4 && strtolower($tenant->status) == 'expired' ) {

                $subscriber->suspendAccount($tenant);                
            }elseif($days == -1 || $days == -2 || $days < -1){

                $subscriber->startProcessingSubscription($tenant);
            }

        }

    }




}
