<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Paystack;
use App\Traits\NonGlobalTenantScopeTrait;

class Order extends Model
{
	
	use NonGlobalTenantScopeTrait;

    protected $guarded = ['id'];
    protected $dates = ['ordered_date', 'expiry_date', 'created_at', 'updated_at', 'deleted_at'];

    public function pilotNumbers(){

        return $this->hasMany('App\Models\Operator\PilotNumber', 'order_id', 'id');
    }

	public function items(){

        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function tenant(){

        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function subscription(){

        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function transaction(){

        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

	public function transactions(){

        return $this->hasMany(Transaction::class, 'order_id', 'id');
    }

	public function generateTransaction(){

        if(!$this->transaction && !$this->payment_status){
            $transaction = Transaction::Create([
                'tenant_id' => $this->tenant_id,
                'subscription_id' => $this->subscription_id,
                'order_id' => $this->id,
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'status' => 'processing',
            	'transaction_method' => $this->subscription->billing_method,
            	'transaction_type' => 'invoice',
            	'transaction_no' => Paystack::genTranxRef(),
            	'currency' => $this->currency,
            	'payment_channel' => 'invoice',
            	'generated_by' => 'user',
            	'amount' => $this->charged,
            	'description' => $this->description,
            ]);

            $this->update(['transaction_id' => $transaction->id]);
            return $transaction;
        }else{
            
            return $this->transaction->update([
                'amount' => $this->charged,
                'transaction_no' => Paystack::genTranxRef(),
                
            ]);

        }

    }

    public function status(){

        if( strtolower($this->status) == 'success' ){

            return "<span class='label-success label'>&#10003; ". ucfirst($this->status) ."</span>";
        }elseif(strtolower($this->status) == 'processing'){
                                    
            return "<span class='label label-primary'>&#10042; ". ucfirst($this->status) ."</span>";
        }elseif(strtolower($this->status) == 'pending'){
                                    
            return "<span class='label label-info'>&#10042; ". ucfirst($this->status) ."</span>";
        }else{
                                        
            return "<span class='label-danger label'>&#10005; ". ucfirst($this->status) ." </span>";

        }

    }

    public function payment_status(){

        if($this->payment_status ){

            return "<span class='label-success label'>&#10003; PAID </span>";
        }else{
                                        
            return "<span class='label-info label'>&#10005; UNPAID </span>";

        }

    }

    public function applyVoucherDiscount(Voucher $voucher){

    	$filtered = $this->items->filter(function($item, $key) use($voucher){
    		
    		if (strpos($item->type, $voucher->voucher_type) !== false) {
			    return true;
			}
			return false;
    	});

        // $this->load(['items' => function($query) use ($voucher){
        //     $query->where('type', 'like', '%'.$voucher->voucher_type ) ;
        // }]);


    	$charged = 0.0;
    	foreach ($filtered as $item) {
    		$charged = $charged + $item->charged; 
    	}

    	if($charged > 0.0 && $voucher->value > 0.0){

    		if($voucher->value < $charged ){

    			$voucher->used = true;
    			$voucher->order_id = $this->id;
    			$voucher->tenant_id = $this->tenant_id;
    			$voucher->update();

    			$this->discount_type = 'voucher';
    			$this->discount = $voucher->value;
    			$this->charged = $this->amount > $voucher->value ? $this->amount - $voucher->value : 0.00;
    			$this->update();

    			$transaction = $this->generateTransaction();
    			$transaction->amount = $this->charged;
    			$transaction->update();

    		}else{

    			$voucher->used = true;
    			$voucher->order_id = $this->id;
    			$voucher->tenant_id = $this->tenant_id;
    			$voucher->update();
    			
    			$this->discount_type = 'voucher';
    			$this->discount = $voucher->value;
    			$this->charged = $this->amount > $charged ? $this->amount - $charged : 0.00;
    			$this->update();

    			$transaction = $this->generateTransaction();
    			$transaction->amount = $this->charged;
    			$transaction->update();

    		}

    	}


    }


}
