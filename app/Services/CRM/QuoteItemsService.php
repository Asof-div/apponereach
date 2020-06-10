<?php 

namespace App\Services\CRM;

use App\Models\QuoteItem;
use App\Models\Quote;
use App\Models\Resource;
use Auth;
use Carbon\Carbon;
use PDF;
use Storage;

use App\Helpers\PaymentTermsHelper;


class QuoteItemsService
{


	public function store($tenant, $data){

        try{
            $tenant = $tenant;
                \Log::log('info', $data);    
            
            $quote = isset($data['quote_id']) ? Quote::find($data['quote_id']) : null;
            $deposit = $data['deposit'] ? (int) $data['deposit'] : 0.0;
            $quote_date = $data['quote_date'] ? $data['quote_date'] : date('Y-m-d');
            if(isset($data['items']) && count($data['items']) > 0 ){

                if(!$quote){
                    $quote = Quote::create([
                            'title' => $data['title'],
                            'tenant_id' => $tenant->id,
                            'opportunity_id' => isset($data['opportunity_id']) ? $data['opportunity_id'] : null,
                            'account_id' => $data['account_id'],
                            'currency_id' => $data['currency_id'],
                            'quote_no' => $this->getQuoteNumber(),
                            'payment_terms' => $data['payment_terms'],
                            'terms' => $data['terms'],
                            'public_note' => $data['public_note'],
                            'private_note' => $data['private_note'],
                            'footer' => $data['footer'],
                            'deposit' => $deposit,
                            'user_id' => Auth::id(),
                        ]);

                }


                if($quote){

                    $quote->items()->delete();
                    $sub_total = 0.0;
                    $revenue = 0.0;
                    
                    if( isset($data['items']) && count($data['items']) > 0 ){
                        foreach ($data['items'] as $key => $item) {  

                            $name =  $item['name'];
                            $uprice =  $item['uprice'];
                            $qty = $item['quantity'];
                            $desc = $item['description'];
                            $optional =  ( (int)isset($item['optional']) == 1 ) ? true : false ;
                            $price =  $optional ? 0.0 : (float) $qty * $uprice;

                            $item = QuoteItem::create([
                                'tenant_id' => $tenant->id,
                                'quote_id' => $quote->id,
                                'name' => $name,
                                'description' => $desc,
                                'quantity' => $qty,
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

                    if(!$quote->account->is_vat_exempted){
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

                    $grand_total = round( ($sub_total - $discount) + $vat, 2);

                    $quote->title = $data['title'];
                    $quote->opportunity_id = isset($data['opportunity_id']) ? $data['opportunity_id'] : null;
                    $quote->account_id = $data['account_id'];
                    $quote->currency_id = $data['currency_id'];
                    $quote->payment_terms = $data['payment_terms'];
                    $quote->po_no = $data['po_no'];
                    $quote->terms = $data['terms'];
                    $quote->public_note = $data['public_note'];
                    $quote->private_note = $data['private_note'];
                    $quote->footer = $data['footer'];
                    $quote->deposit = $deposit;
                    $quote->discount = $discount;
                    $quote->discount_type = $discount_type;
                    $quote->discount_rate = $discount_rate;
                    $quote->validated = 0;
                    $quote->quote_date = $quote_date;
                    $quote->expiration_date = $data['expiration_date'];
                    $quote->vat = $vat;
                    $quote->vat_type = $vat_type;
                    $quote->vat_rate = $vat_rate;
                    $quote->sub_total = $sub_total;
                    $quote->grand_total = $grand_total;
                    $quote->balance_due = round($grand_total - $deposit ,2);
                    $quote->update();
                    

                    // $quote->update([

                    //     'title' => $data['title'],
                    //     'opportunity_id' => isset($data['opportunity_id']) ? $data['opportunity_id'] : null,
                    //     'account_id' => $data['account_id'],
                    //     'currency_id' => $data['currency_id'],
                    //     'payment_terms' => $data['payment_terms'],
                    //     'po_no' => $data['po_no'],
                    //     'terms' => $data['terms'],
                    //     'public_note' => $data['public_note'],
                    //     'private_note' => $data['private_note'],
                    //     'footer' => $data['footer'],
                    //     'deposit' => $deposit,
                    //     'discount' => $discount,
                    //     'discount_type' => $discount_type,
                    //     'discount_rate' => $discount_rate,
                    //     'validated' => 0,
                    //     'quote_date' => $quote_date,
                    //     'expiration_date' => $data['expiration_date'],
                    //     'vat' => $vat,
                    //     'vat_type' => $vat_type,
                    //     'vat_rate' => $vat_rate,
                    //     'sub_total' => $sub_total,
                    //     'grand_total' => $grand_total,
                    //     'balance_due' => round($grand_total - $deposit ,2),
                        
                    // ]);
                    
                    $quote->contacts()->detach();

                    if(isset($data['contacts'])){
                        $index = 0;
                        foreach ($data['contacts'] as $value) {
                            if($index >= 2 ) break; 
                            $quote->addContact((int)$value);
                            $index = $index + 1;
                        }
                    }

                    $payment = $quote->payment_terms;

                    $now = Carbon::now();
                    $revision = $quote->historys->count() < 1 ? 0 : $quote->historys->last()->pivot->revision;
                    $revision = $revision + 1;

                    $ref = str_replace(' ', '_', $quote->quote_no)."_".$now->format('Y-M-d_H-i') ;
                    $label = $quote->currency->symbol .' '. number_format($quote->grand_total) .', '. $now->toDayDateTimeString();

                    $pdf = PDF::loadView('app.tenant.crm.pdf.quote',['quote'=>$quote , 'ref' => $ref, 'tenant' => $tenant, 'payment' => $payment])->setPaper('A4');
                    $dom_pdf = $pdf->getDomPDF();
                    $font = $dom_pdf->getFontMetrics()->get_font("helvetica", "bold");
                    $canvas = $dom_pdf->get_canvas();
                    $canvas->page_text(515, 820, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0,0,0));


                    $path = $tenant->tenant_no."/quote/{$quote->quote_no}/".$now->format('Y-M-d')."/";
                    Storage::disk('local')->put("public/".$path . $ref.".pdf", $pdf->output());
                    $this->storage($quote, $path, $ref, $revision, $label);                   
                    
                    $quote->update(['pdf_path' => $path . $ref.".pdf" ]);
                
                    shell_exec('chmod -Rf 777 '.storage_path('public/'.$tenant->tenant_no."/quote/{$quote->quote_no}/"));
                }

                return ['response' => [ 'success' => 'Items Successfully Saved' ], 'code' => 200 ];
            }else{

                \Log::log('info', $data);    
                return ['response' => [ 'error' => 'Quote items empty' ], 'code' => 403 ];        
            }

        }catch(\Expection $e){
            \Log::log('info', $e->getMessage());

            return ['response' => [ 'error' => 'Unable to save quote' ], 'code' => 403 ];        

        }        
	}


    function storage($quote, $path, $ref, $revision, $label){

        $res = new Resource;
        $res->original_name = $ref.'.pdf';
        $res->ext = 'pdf';
        $res->filename = $ref;
        $res->size = 100;
        $res->mime_type = 'application/pdf';
        $res->error = 0;
        $res->path = $path.$ref.".pdf";
        $res->user_id = Auth::id();
        $res->category = "Quote";
        $res->file_type = "PDF";
        $res->module_id = $quote->id;
        $res->module_type = "App\Models\Quote";
        $res->save();

        $quote->historys()->save($res, ['revision' => $revision, 'update' => $label, 'created_at' => Carbon::now()]);
    }



    public function status($tenant, $data){

        try{
            $tenant = $tenant;
            
            $quote = Quote::find($data['quote_id']);
            
            if($quote){

                $quote->update([
                    'status' => $data['status'],          
                ]);
                

                return ['response' => [ 'success' => 'Quote Successfully Updated' ], 'code' => 200 ];
            }else{

                return ['response' => [ 'error' => 'Quote not found' ], 'code' => 422 ];        
            }

        }catch(\Expection $e){
            return ['response' => [ 'error' => 'Unable to save quote' ], 'code' => 422 ];        

        }        
    }


private function getQuoteNumber(){
        $quotes = Quote::company(Auth::user()->tenant_id)->get()->pluck('quote_no');

        $num = count($quotes) + 1;
        if(!$quotes->search(sprintf("%05d", $num)) ){
            return sprintf("%05d", $num);
        }else{
            $this->random($quotes, $num);
            return sprintf("%05d", $num);
        }
        
    }

    function random($quotes, &$num){
        while($quotes->search(sprintf("%05d", $num)) !== false ){
            $num = $num + 1;
        }
    }



}