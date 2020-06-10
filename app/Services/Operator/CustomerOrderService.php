<?php 
namespace App\Services\Operator;

use App\Models\Operator\PilotNumber;
use App\Models\Billing;
use App\Models\PilotLine;
use App\Models\PaymentTransaction;
use App\Models\Tenant;
use Carbon\Carbon;

use Auth;

class CustomerOrderService {


	public function __construct($tenant=null)
    {

        $this->tenant = $tenant;

	}


    public function saveMSISDNOrder($data): Array
    {

        $ordered_items = [];
        
        if( isset($data['product']) && count($data['product']) > 0){
            
            $tenant = Tenant::find($data['customer_id']);
            $amount = 0;
            $sum_total = 0;
            $start = Carbon::now();
            $due = $start->copy();
            $due->addHours(3);
            foreach ($data['product'] as $key => $product) {
                $amount = $data['qty'][$key] * $data['price'][$key];
                $ordered_items[] =  ['product' => strtoupper($product) , 'type' => strtoupper($product), 'items' => $data['qty'][$key], 'description' => $this->getDescription($product) . $data['price'][$key] , 'period' => '1 Month', 'amount' => $amount, 'charged' => $amount, 'status' => 1 ];
                $sum_total += $amount;
            }

    
            $billing = Billing::create([
                'tenant_id' => $tenant->id,
                'email' => strtolower($tenant->info->email),
                'firstname' => $tenant->name,
                'lastname' => $tenant->name,
                'due_date' => $due,
                'billing_method' => 'postpaid',
                'payment_method' => 'postpaid',
                'amount' => (float) $sum_total,
                'charged' => (float) $sum_total ,
                'billing_type' => 'Addons',
                'status' => 'processing',
                'description' => "Purchase addons ",
                'ordered_items' => json_encode($ordered_items),
                'subscription_id' => $data['subscription_id'],
                'payment_status' => 'unpaid',
                'issuer_type' => 'operator',
                'issuer_name' => Auth::user()->name,
                'issuer_email' => Auth::user()->email,
            ]);

            $url = route('operator.customer.billing.order.details', [$tenant->id, $billing->id]);

            return ['status' => 'success', 'success' => 'Order successfully saved', 'order' => $billing, 'url' => $url ];

        }else{

            return ['status' => 'error', 'error' => ['No product selected']];
        }


        return [];
    }

    public function confirm($data): Array
    {
        
        $type = isset($data['order_type']) ? $data['order_type'] : '';
        
        switch (strtolower($type)) {
            case 'addons':
                return $this->confirmMSISDNOrder($data);
                break;
            
            default:
                return ['status' => 'error', 'error'=>['Invalid Order'] ];
                break;
        }
    }

    private function confirmMSISDNOrder($data): Array
    {
        $customer = Tenant::find($data['customer_id']);
        $order = Billing::where('tenant_id', $data['customer_id'])->where('id', $data['order_id'])->get()->first();
        $subscription = $order->subscription;
        $items = [];
        foreach( json_decode($order->ordered_items, true) as $item ){
            if(strtoupper($item['type']) == 'MSISDN'){
                $current_slot = (int) $customer->info->extra_number;
                $current_slot += (int) $item['items'];
                $customer->info->update(['extra_number' => $current_slot]);
            }
            $items[] = $item;
        }

        $order->update([ 'status' => 'success', 'ordered_items' => json_encode($items), 'paid_date' => Carbon::now(),]);
        $subscription->update([
            'extra_msisdn' => json_encode([
                    'items' =>  $current_slot,
                    'price' => 500 * $current_slot
                ]),
            'total' => (float) $subscription->amount + (500 * $current_slot),
        ]);

        return ['status' => 'success', 'success' => 'Order have been confirmed', 'order' => $order ];
    }


    private function getDescription($data)
    {
        
        switch ( strtolower($data) ) {
            case 'msisdn':
                $title = "Purchase Extra MSISDN Slot ";
                
                break;
            
            default:
        
                $title = $data;
                break;
        }

        return $title;
    }




    public function cancel($data): Array
    {
        $type = isset($data['order_type']) ? $data['order_type'] : '';

        switch (strtolower($type)) {
            case 'addons':
                return $this->cancelAddonsOrder($data);
                break;
            
            default:
                return ['status' => 'error', 'error'=>['Invalid Order'] ];
                break;
        }
    }

    private function cancelAddonsOrder($data): Array
    {
        $customer = Tenant::find($data['customer_id']);
        $order = Billing::where('tenant_id', $data['customer_id'])->where('id', $data['order_id'])->get()->first();
       
        $items = [];
        foreach( json_decode($order->ordered_items, true) as $item ){
            $item['status'] = 0;
                
            $items[] = $item;
        }

        $order->update([ 'status' => 'cancel', 'ordered_items' => json_encode($items), 'paid_date' => null,]);


        return ['status' => 'success', 'success' => 'Order have been canceled', 'order' => $order ];
    }

}