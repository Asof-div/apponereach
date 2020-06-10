<?php
namespace App\Services\Operator;

use Exception;

use App\Models\Tenant;
use App\Models\TenantInfo;
use App\Models\Package;
use App\Models\User;
use App\Models\PostPaid;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Operator\PilotNumber;
use App\Models\PilotLine;
use App\Models\Ticket;
use App\Models\Call;

use Carbon\Carbon;

use Auth;
use DB;
/**
* 
*/
class CustomerUnboardingService 
{
	
	public function delete(Tenant $customer){

		try{
			DB::beginTransaction();

				$pilot_numbers = PilotNumber::where('tenant_id', $customer->id)->get();
				foreach ($pilot_numbers as $pilot) {
		            $pilot->update([
		            	'tenant_id' => null, 
		            	'available' => 1, 
		            	'release_time' => null, 
		            	'status' => 'unallocated', 
		            	'provisioning' => 'Not Provisioned', 
		            	'order_id' => null,
		            	'purchased' => false,
		            ]);  
					
				}

				$pilots = PilotLine::company($customer->id)->get();
				foreach ($pilots as $pilot) {
		            $pilot->delete();  
					
				}

				foreach (User::company($customer->id) as $user) {
		            $user->delete();  
					
				}
				foreach (Order::company($customer->id) as $order) {
		            $order->delete();  
					
				}
				foreach (Transaction::company($customer->id) as $payment) {
		            $payment->delete();  
					
				}
				foreach (Subscription::company($customer->id) as $subscription) {
		            $subscription->delete();  
					
				}
				foreach (Ticket::company($customer->id) as $ticket) {
		            $ticket->delete();  
					
				}
				foreach (Call::company($customer->id) as $cdr) {
		            $cdr->delete();  
					
				}

		        shell_exec('rm -r '. storage_path('/app/public/') .$customer->tenant_no);
		        shell_exec('rm -r '. storage_path('/app/public/tenants_logs/') .$customer->tenant_no);

				$customer->delete();
				\Log::log('info', $customer);

	        DB::commit();
	        return ['status' => ['success' => 'Customer Account Successfully Deleted', 'url' => route('operator.customer.index', [$customer->id]) ], 'code' => 200];
	    }catch(Exception $e){
	    	DB::rollback();
	        return ['status' => ['error' => ['Unable to register customer info']], 'code' => 422];
	    }

	}




}