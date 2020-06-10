<?php

namespace App\Services\Cart;

use App\Services\Cart\Interface\CartService;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;


class TopupItemService implements CartService {

	public function addItem(Order $order, Request $request) : Order {

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

    public function removeItem(Order $order, Request $request){

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



    private function checkoutItem(Transaction $payment, OrderItem $item){
        

        $subscription = $payment->subscription;
        $tenant = $payment->tenant;

        $credits = (float) $tenant->credits;
        $amount = (float) $item->amount;

        $tenant->update([
                'credits' => $credits + $amount,
            ]);

    }

}