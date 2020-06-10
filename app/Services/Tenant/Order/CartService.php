<?php

namespace App\Services\Tenant\Order;

use Illuminate\Http\Request;

use Carbon\Carbon;

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

class CartService
{


	public function addTopup($order, Request $request){

        $order->load(['items']);
        $filtered = $order->items->filter(function($item, $key) use ($request){
            return $item->type == 'topup' && $item->amount == $request->amount;
        })->first();

        if($filtered){
            $quantity = (int)$filtered->quantity;
            $filtered->update([
                'quantity' => ++$quantity,
                'charged' => $quantity * $request->amount,
            ]);
        }else{

            OrderItem::create([
                'tenant_id' => $order->tenant_id,
                'order_id' => $order->id,
                'quantity' => 1,
                'amount' => $request->amount,
                'charged' => $request->amount,
                'currency' => 'NGN',
                'type' => 'topup',
                'name' => 'Line Recharge @ '.$request->amount,
                'item' => 'Line Recharge @ '.$request->amount,
                'description' =>'Line Recharge @ '.$request->amount,
        
            ]); 

        }

        $order->load(['items']);
        $amount = 0;

         if($order){
            foreach ($order->items as $item) {
                $amount = $amount + (float) $item->charged;
            }
            $order->amount = (float) $amount ;
            $order->charged = (float) $amount ;
            $order->update();
        }

        return $order->load(['items']);
    }

    public function reduceTopup($order, Request $request){

        $order->load(['items']);
        $filtered = $order->items->filter(function($item, $key) use ($request){
            return $item->type == 'topup' && $item->id == $request->id;
        })->first();

        if($filtered){
            $quantity = (int)$filtered->quantity;
            if($quantity > 1){

                $filtered->update([
                    'quantity' => --$quantity,
                    'charged' => $quantity * $filtered->amount,
                ]);
            }
            else{
                $filtered->delete();
            }
        }


        $order->load(['items']);
        $amount = 0;

         if($order){
            foreach ($order->items as $item) {

                $amount = $amount + (float) $item->charged;

            }
            $order->amount = (float) $amount ;
            $order->charged = (float) $amount ;
            $order->update();
        }

        return $order->load(['items']);
    }


    public function addNumber($order, $numbers, Request $request){

        
        $user = $request->user();
        $tenant = $user->tenant;

        $pilot_price = 0;

        $filteredNumber = $numbers->filter(function($item, $key) use ($request){
        	return $item->type == $request->type;
        });
        
        $quantity = $filteredNumber->count();
        $price = 0;
        if($quantity > 0){

	        $price = $filteredNumber->first()->line_type->price;
        }
        $pilot_price = $quantity * $price;


        $order->load(['items']);
        $filtered = $order->items->filter(function($item, $key) use ($request){
            return strtolower($item->type) == strtolower($request->type.'_line');
        })->first();

        if($filtered){
            $filtered->update([
                'quantity' => $quantity,
                'charged' => $pilot_price,
                'item' => implode(", ", $filteredNumber->pluck('number')->toArray() ),
                'description' =>'DID LINES: '. implode(", ", $filteredNumber->pluck('number')->toArray() ),
            ]);
        }else{

            OrderItem::create([
                'tenant_id' => $order->tenant_id,
                'order_id' => $order->id,
                'quantity' => $quantity,
                'amount' => $filteredNumber->first()->line_type->price,
                'charged' => $pilot_price,
                'currency' => 'NGN',
                'type' => $filteredNumber->first()->line_type->name.'_line',
                'name' => $filteredNumber->first()->line_type->label.' Line',
                'item' => implode(", ", $filteredNumber->pluck('number')->toArray() ),
                'description' =>'Regular DID LINES: '. implode(", ", $filteredNumber->pluck('number')->toArray() ),
        
            ]); 

        }

        $order->load(['items']);
        $amount = 0;

         if($order){
            foreach ($order->items as $item) {
                $amount = $amount + (float) $item->charged;
            }
            $order->amount = (float) $amount ;
            $order->charged = (float) $amount ;
            $order->update();
        }

        return $order->load(['items']);


    }

    public function removeNumber($order, $numbers, Request $request){

        
        $user = $request->user();
        $tenant = $user->tenant;

        $pilot_price = 0;

        $filteredNumber = $numbers->filter(function($item, $key) use ($request){
        	return $item->type == $request->type;
        });
        
        $quantity = $filteredNumber->count();
        $price = 0;
        if($quantity > 0){

	        $price = $filteredNumber->first()->line_type->price;
        }
        $pilot_price = $quantity * $price;



        $order->load(['items']);
        $filtered = $order->items->filter(function($item, $key) use ($request){
            return strtolower($item->type) == strtolower($request->type.'_line');
        })->first();

        if($filtered){
            if($quantity > 1){
		        $pilot_price = $quantity * $price;

                $filtered->update([
                    'quantity' => --$quantity,
                    'charged' => $pilot_price,
	                'description' =>'DID LINES: '. implode(", ", $filteredNumber->pluck('number')->toArray() ),
                ]);
            }
            else{
                $filtered->delete();
            }

        }

        $order->load(['items']);
        $amount = 0;

         if($order){
            foreach ($order->items as $item) {
                $amount = $amount + (float) $item->charged;
            }
            $order->amount = (float) $amount ;
            $order->charged = (float) $amount ;
            $order->update();
        }

        return $order->load(['items']);


    }


    public function verifiedCheckout(Transaction $payment, User $user){
        
        
        $order = $payment->order;
        $subscription = $payment->subscription;
        $order->load(['items']); 
        
        foreach ($order->items as $item) {
            $type = strtolower($item->type);
            
            if($type == 'plan'){
                $this->activatePlan($payment, $item);
            }
            elseif ($type == 'topup') {
                $this->activateTopup($payment, $item);
                
            }elseif($type == 'regular_line'){

                $this->activateNumber($payment, $item);
            }
        
        }
        
        $order->update(['status' => 'success', 'payment_status' => true]);
        $subscription->update(['order_id' => null ]);

    }

    private function activatePlan($payment, $item){

        $planHelper = new PlanHelper; 

        $now = Carbon::now();
        $subscription = $payment->subscription;
        $tenant = $payment->tenant;



        $planHelper->setPackage($subscription->package);
        $planHelper->setPeriod($subscription->cycle);
        $start = Carbon::now();
        $planHelper->setStartDate($start);

        $end = $planHelper->getEndDate();
        $subscription->update([
                    'payment_status' => 'paid',
                    'status' => 'success',
                    'start_time' => $start,
                    'end_time' => $end
                ]);

        $tenant->update([
                'status' => 'Activated',
                'expired' => 0,
                'expiration_date' => $end,
                'activated' => 1,
            ]);

        $tenant->info->update(['activation_date' => Carbon::now()]);

    }

    private function activateTopup($payment, $item){
        

        $subscription = $payment->subscription;
        $tenant = $payment->tenant;

        $credits = (float) $tenant->credits;
        $amount = (float) $item->amount;

        $tenant->update([
                'credits' => $credits + $amount,
            ]);

    }

    private function activateNumber($payment, $item){

        $subscription = $payment->subscription;
        $tenant = $payment->tenant;

        foreach (explode(",", $item->item) as $number) {

            $number = trim($number);
            $pilot = PilotNumber::where('number', $number)->where('tenant_id', $tenant->id)->where('purchased', 0)->where('available',0)->first();
            if($pilot){

                $pilot->update(['status' => 'allocated', 'purchased' => 1, 'available' => 0]);

                PilotLine::create([
                    'tenant_id' => $pilot->tenant_id,
                    'number' => $pilot->number,
                    'label' => $pilot->label,
                    'type_id' => $pilot->type_id,

                ]);

            }
        }
    }


}