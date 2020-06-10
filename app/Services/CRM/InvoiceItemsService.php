<?php 

namespace App\Services\CRM;

use App\Models\InvoiceItem;
use App\Models\Quote;
use App\Models\Invoice;
use App\Models\Resource;
use Auth;
use Carbon\Carbon;
use PDF;
use Storage;

use App\Helpers\PaymentTermsHelper;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

class InvoiceItemsService
{

	public function __construct(){

        $this->payment = new PaymentTermsHelper();
	}

	public function store($data){

        try{
            $tenant = TenantManager::get();
            
            $invoice = Invoice::find($data['invoice_id']);
            $deposit = $data['deposit'] ? (int) $data['deposit'] : 0.0;
            $invoice_date = $data['invoice_date'] ? $data['invoice_date'] : date('Y-m-d');
            if(isset($data['description']) && count($data['description']) > 0 ){

                if(!$invoice){
                    $invoice = Invoice::create([
                            'title' => $data['title'],
                            'tenant_id' => $tenant->id,
                            'opportunity_id' => $data['opportunity_id'],
                            'account_id' => $data['account'],
                            'quote_id' => $data['quote_id'],
                            'currency_id' => $data['currency_id'],
                            'invoice_no' => $data['invoice_no'],
                            'payment_terms' => $data['payment_terms'],
                            'terms' => $data['terms'],
                            'public_note' => $data['public_note'],
                            'private_note' => $data['private_note'],
                            'footer' => $data['footer'],
                            'deposit' => $deposit,
                            'user_id' => Auth::id(),
                        ]);
                }


                if($invoice){

                    $invoice->items()->delete();
                    $sub_total = 0.0;
                    $revenue = 0.0;
                    
                    if( isset($data['description']) && count($data['description']) > 0 ){
                        foreach ($data['description'] as $key => $desc) {  

                            $name =  $data['name'][$key];
                            $uprice =  $data['uprice'][$key];
                            $qty = $data['quantity'][$key];
                            $optional =  ( (int)isset($data['optional'][$key]) == 1 ) ? true : false ;
                            $price =  $optional ? 0.0 : (float) $qty * $uprice;

                            $item = InvoiceItem::create([
                                'tenant_id' => $tenant->id,
                                'invoice_id' => $invoice->id,
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

                    if(!$invoice->account->is_vat_exempted){
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

                    $invoice->update([

                        'title' => $data['title'],
                        'opportunity_id' => $data['opportunity_id'],
                        'account_id' => $data['account'],
                        'currency_id' => $data['currency_id'],
                        'payment_terms' => $data['payment_terms'],
                        'po_no' => $data['po_no'],
                        'terms' => $data['terms'],
                        'public_note' => $data['public_note'],
                        'private_note' => $data['private_note'],
                        'footer' => $data['footer'],
                        'deposit' => $deposit,
                        'discount' => $discount,
                        'discount_type' => $discount_type,
                        'discount_rate' => $discount_rate,
                        'validated' => 0,
                        'invoice_date' => $invoice_date,
                        'expiration_date' => $data['expiration_date'],
                        'vat' => $vat,
                        'vat_type' => $vat_type,
                        'vat_rate' => $vat_rate,
                        'sub_total' => $sub_total,
                        'grand_total' => $grand_total,
                        'balance_due' => round($grand_total - $deposit ,2),
                        
                    ]);
                    

                    $invoice->contacts()->detach();

                    if(isset($data['contacts'])){
                        $index = 0;
                        foreach ($data['contacts'] as $value) {
                            if($index >= 2 ) break; 
                            $invoice->addContact((int)$value);
                            $index = $index + 1;
                        }
                    }

                    $payment = $this->payment->getTermByName($invoice->payment_terms)['description'];

                    $now = Carbon::now();
                    $revision = $invoice->historys->count() < 1 ? 0 : $invoice->historys->last()->pivot->revision;
                    $revision = $revision + 1;

                    $ref = str_replace(' ', '_', $invoice->invoice_no)."_".$now->format('Y-M-d_H-i') ;
                    $label = $invoice->currency->symbol .' '. number_format($invoice->grand_total) .', '. $now->toDayDateTimeString();

                    $pdf = PDF::loadView('app.tenant.crm.pdf.invoice',['invoice'=>$invoice , 'ref' => $ref, 'tenant' => $tenant, 'payment' => $payment])->setPaper('A4');
                    $dom_pdf = $pdf->getDomPDF();
                    $font = $dom_pdf->getFontMetrics()->get_font("helvetica", "bold");
                    $canvas = $dom_pdf->get_canvas();
                    $canvas->page_text(515, 820, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0,0,0));


                    $path = $tenant->tenant_no."/invoice/{$invoice->invoice_no}/".$now->format('Y-M-d')."/";
                    Storage::disk('local')->put("public/".$path . $ref.".pdf", $pdf->output());
                    $this->storage($invoice, $path, $ref, $revision, $label);                   
                    
                    $invoice->update(['pdf_path' => $path . $ref.".pdf" ]);

                    shell_exec('chmod -Rf 777 '.storage_path('public/'.$tenant->tenant_no."/invoice/{$invoice->invoice_no}/"));
                
                }

                return ['response' => [ 'success' => 'Items Successfully Saved' ], 'code' => 200 ];
            }else{

                return ['response' => [ 'error' => 'Invoice items empty' ], 'code' => 422 ];        
            }

        }catch(\Expection $e){
            return ['response' => [ 'error' => 'Unable to save invoice' ], 'code' => 422 ];        

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
        $res->category = "Invoice";
        $res->file_type = "PDF";
        $res->module_id = $quote->id;
        $res->module_type = "App\Models\Invoice";
        $res->save();

        $quote->historys()->save($res, ['revision' => $revision, 'update' => $label, 'created_at' => Carbon::now()]);
    }


    public function status($data){

        try{
            $tenant = TenantManager::get();
            
            $invoice = Invoice::find($data['invoice_id']);
            
            if($invoice){

                $invoice->update([
                    'status' => $data['status'],          
                ]);
                

                return ['response' => [ 'success' => 'Invoice Successfully Updated' ], 'code' => 200 ];
            }else{

                return ['response' => [ 'error' => 'Invoice not found' ], 'code' => 422 ];        
            }

        }catch(\Expection $e){
            return ['response' => [ 'error' => 'Unable to save invoice' ], 'code' => 422 ];        

        }        
    }


    public function convertQuote(&$invoice, $clone){

        try{
            
            $tenant = TenantManager::get();

            $invoice->title = $clone->title;
            $invoice->tenant_id = $clone->tenant_id;
            $invoice->quote_id = $clone->quote_id;
            $invoice->opportunity_id = $clone->opportunity_id;
            $invoice->account_id = $clone->account_id;
            $invoice->currency_id = $clone->currency_id;
            $invoice->payment_terms = $clone->payment_terms;
            $invoice->terms = $clone->terms;
            $invoice->public_note = $clone->public_note;
            $invoice->private_note = $clone->private_note;
            $invoice->footer = $clone->footer;
            $invoice->deposit = $clone->deposit;
            $invoice->po_no = $clone->po_no;
            $invoice->discount = $clone->discount;
            $invoice->discount_type = $clone->discount_type;
            $invoice->discount_rate = $clone->discount_rate;
            $invoice->invoice_date = $clone->quote_date;
            $invoice->expiration_date = $clone->expiration_date;
            $invoice->vat = $clone->vat;
            $invoice->vat_type = $clone->vat_type;
            $invoice->vat_rate = $clone->vat_rate;
            $invoice->sub_total = $clone->sub_total;
            $invoice->grand_total = $clone->grand_total;
            $invoice->balance_due = $clone->balance_due;
            $invoice->save();
            foreach ($clone->contacts as $contact) {
        
                $invoice->contacts()->save($contact);
        
            }
            foreach ($clone->items as $item) {
                $newItem = InvoiceItem::create([
                    'tenant_id' => $item->tenant_id,
                    'invoice_id' => $invoice->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'list_order' => $item->list_order,
                    'optional' => $item->optional,
                    'uprice' => $item->uprice, 
                    'price' => $item->price,

                ]); 
                $invoice->items()->save($newItem);
        
            }

            $payment = $this->payment->getTermByName($invoice->payment_terms)['description'];

            $now = Carbon::now();
            $revision = $invoice->historys->count() < 1 ? 0 : $invoice->historys->last()->pivot->revision;
            $revision = $revision + 1;

            $ref = str_replace(' ', '_', $invoice->invoice_no)."_".$now->format('Y-M-d_H-i') ;
            $label = $invoice->currency->symbol .' '. number_format($invoice->grand_total) .', '. $now->toDayDateTimeString();

            $pdf = PDF::loadView('app.tenant.crm.pdf.invoice',['invoice'=>$invoice , 'ref' => $ref, 'tenant' => $tenant, 'payment' => $payment])->setPaper('A4');
            $dom_pdf = $pdf->getDomPDF();
            $font = $dom_pdf->getFontMetrics()->get_font("helvetica", "bold");
            $canvas = $dom_pdf->get_canvas();
            $canvas->page_text(515, 820, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0,0,0));


            $path = $tenant->tenant_no."/invoice/{$invoice->invoice_no}/".$now->format('Y-M-d')."/";
            Storage::disk('local')->put("public/".$path . $ref.".pdf", $pdf->output());
            $this->storage($invoice, $path, $ref, $revision, $label);                   
            
            $invoice->update(['pdf_path' => $path . $ref.".pdf" ]);
                

            return ['response' => [ 'success' => 'Quote Successfully Converted', 'url' => route('tenant.crm.invoice.edit', [$tenant->domain, $invoice->id]) ], 'code' => 200 ];
          

        }catch(\Expection $e){
            return ['response' => [ 'error' => 'Unable to save invoice' ], 'code' => 422 ];        

        }        
    }





}