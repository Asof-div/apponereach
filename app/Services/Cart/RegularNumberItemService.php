<?php 

namespace App\Services\Cart;

use App\Services\Cart\Interface\CartService;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;


class RegularNumberItemService implements CartService {

	$numbers = array();

	public function __construct($numbers = array()){

		$this->numbers = $numbers;
	}

    public function addItem(Order $order, Request $request){

        $numbers = $this->numbers;
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

    public function removeItem(Order $order, Request $request){

        $number = $this->numbers;
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

    public function checkoutItem(Transaction $payment, OrderItem $item){

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