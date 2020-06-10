<?php 

namespace App\Repos;

use App\Models\OpportunityItem;
use App\Models\OpportunityLine;


use App\Scopes\Facade\TenantManagerFacade as TenantManager;

class OpportunityItemsRepo extends DestinationRepo
{

	public function __construct(){

	}

	public function store($data){

        
        $opportunity = OpportunityLine::find($data['opportunity_line_id']);
        $tenant = TenantManager::get();

        \Log::log('info', $data);

        if($opportunity){

            $opportunity->items()->delete();
            $sub_total = 0.0;
            $revenue = 0.0;
            
            if( isset($data['description']) && count($data['description']) > 0 ){
                foreach ($data['description'] as $key => $desc) {  

                    $uprice =  $data['uprice'][$key];
                    $qty = $data['quantity'][$key];
                    $optional =  ( (int)$data['optional'][$key] == 1 ) ? true : false ;
                    $price =  $optional ? 0.0 : (float) $qty * $uprice;

                    $item = OpportunityItem::create([
                        'tenant_id' => $tenant->id,
                        'opportunity_line_id' => $opportunity->id,
                        'description' => $desc,
                        'quantity' => $qty,
                        'uprice' => $uprice,
                        'list_order' => $key,
                        'optional' => $optional,
                        'uprice' => round($uprice, 2), 
                        'price' => round($price, 2),

                    ]); 
                    $sub_total = round($sub_total + $price, 2);
                    
                }
            }
            
            $discount = 0.0;
            $discount_type = isset($data['discount_type']) ? $data['discount_type'] : 'percentage' ;
            $discount_rate = isset($data['discount_rate']) ? round($data['discount_rate'], 2) : 0.0;

            $vat = 0.0;
            $vat_type = isset($data['vat_type']) ? $data['vat_type'] : 'percentage' ;
            $vat_rate = isset($data['vat_rate']) ? round($data['vat_rate'], 2) : 0.0;
            
            if($discount_type == 'percentage'){

                $discount = round($sub_total * ($discount_rate/100), 2);
            
            }else{
                
                $discount = $discount_rate;
            }

            if(!$opportunity->opportunity->account->is_vat_exempted){
                if($vat_type == 'percentage'){
                   
                    $vat = round(($sub_total - $discount) * ($vat_rate/100), 2);
                
                }else{
                    
                    $vat = $vat_rate;
                
                }
            }else{

                $data['vat_val'] = 0;
                $vat_rate = 0;
                $vat = 0;
            }


            $opportunity->update([

                'discount' => $discount,
                'discount_type' => $discount_type,
                'discount_rate' => $discount_rate,
                'validated' => 0,
                'vat' => $vat,
                'vat_type' => $vat_type,
                'vat_rate' => $vat_rate,
                'sub_total' => $sub_total,
                'grand_total' => round( ($sub_total - $discount) + $vat, 2),
                
            ]);
        
        }        
	}







}