<?php

namespace App\Repos;

use Carbon\Carbon;
use App\Models\Subscription;
use Auth;
use App\Services\SubscriberService;

class TenantGateValidator
{
	public $user = null;
	public $tenant = null;

    public function __construct(SubscriberService $subscriber)
    {
        $this->subscriber = $subscriber;
    }

	public function set($tenant, $user){
		$this->tenant = $tenant;
		$this->user = $user;
	}

	public function manager(){

		if(!is_null($this->tenant) && !is_null($this->user) ){

			if( ($this->tenant->id == $this->user->tenant_id) && $this->user->manager == 1){

				return true;
			}else{
				return false;
			}

		}
		else{

			return false;
		}

	}

	public function match(){

		if(!is_null($this->tenant) && !is_null($this->user) ){

			if( $this->tenant->id == $this->user->tenant_id ){

				return true;
			}else{
				return false;
			}

		}
		else{

			return false;
		}

	}

	public function subscription($tenant){
		
        $today = Carbon::today();
		if(!is_null($tenant) && Auth::check()){

			$tenant->load(['subscriptions' => function($query) use ($today){
	            $query->whereDate('start_time', '<=', $today)->whereDate('end_time', '>=', $today);
	        }]);

			if($tenant->subscriptions->count() > 0 ){
				$subscription = $tenant->subscriptions->first(); 
				if( strtolower($subscription->status) == 'success' && strtolower($tenant->status) == 'activated'){

					return true;
				}elseif (strtolower($subscription->status) == 'pending' && strtolower($tenant->status) == 'expired' ) {
					
					$days = $today->diffInDays($subscription->start_time, false);
					if($days >= 0){
						return true;
					}elseif ($days >= -3) {
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{

				$this->generator($tenant);

				return false;
			}

		}
		else{

			return false;
		}

	}


	public function expiring($tenant){
		
        $today = Carbon::today();
		if(!is_null($tenant) && Auth::check()){

			$tenant->load(['subscriptions' => function($query) use ($today){
	            $query->whereDate('end_time', '>=', $today)->where('status', 'pending');
	        }]);

			if($tenant->subscriptions->count() > 0 ){
				return true;
			}else{

				return false;
			}

		}
		else{

			return false;
		}

	}

	private function generator($tenant){
		if($tenant->subscription){
			$subscriber = $this->subscriber;
			$subscriber->createNextSubscription($tenant, $tenant->subscription->end_time);
            $subscriber->generatePaymentStatement($tenant, $tenant->subscription);
		}
		
	}

}