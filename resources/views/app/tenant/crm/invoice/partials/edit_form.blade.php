

<form id="edit_invoice_form" class="form-horizontal" name="13" action="" enctype='multipart/form-data' method='post'>
    {{ csrf_field() }}
    <div class="col-md-12">
        <h4 class="clearfix">Invoice Information <span class="pull-right"> {!! $invoice->status() !!} </span></h4>
        <hr class="horizonal-line-thick">
    </div>
    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
    <input type="hidden" name="quote_id" value="{{ $invoice->quote_id }}">
    <input type="hidden" name="opportunity_id" value="{{ $invoice->opportunity_id }}">
    <input type="hidden" name="currency_id" value="{{ $invoice->currency_id }}">
    <input type="hidden" name="account" value="{{ $invoice->account_id }}">

    <div class="col-md-12 ">
        <div class="form-group">
            <label class="col-md-4">Title </label>
            <div class="col-md-8">
                <input type="text" name="title" class="form-control" value="{{ $invoice->title }}">
            </div>
        </div>
    </div>  

    <div class="col-md-4 ">
        <div class="form-group">
            <label class="col-md-4">Client <i class="fa fa-asterisk text-danger"></i></label>
            <div class="col-md-8">

                <div class="account-display {{ $account ? '' : 'hidden' }}">
                    <h4> {{ $account->name }} </h4>
                    <a target="blank" href="{{ route('tenant.crm.account.show', [$tenant->domain, $invoice->account->name]) }}"> View Client </a>
                </div>

                <br>
                <div class="contact-list">
                    @foreach($account->contacts as $contact)
                        <div> <label><input name='contacts[]' {{ $invoice->hasContact($contact->id) ? 'checked' : '' }} value="{{ $contact->id }}" type='checkbox' > 
                            {{ $contact->name }} -- {{ $contact->email }} 
                            </label> </div>
                    @endforeach 
                </div>
            </div>
        </div>
    </div>  

    <div class="col-md-4 overflow-x-auto">
        <div class="form-group clearfix">
            <label class="col-md-4">Invoice Date <i class="fa fa-asterisk text-danger"></i></label>
            <div class="col-md-8">
                <input type="text" name="invoice_date" class="datepicker form-control" value="{{ $invoice->invoice_date }}">
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-4">Valid Until </label>
            <div class="col-md-8">
                <input type="text" name="expiration_date" class="datepicker form-control" value="{{ $invoice->expiration_date }}">
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-4">Partial/Depos </label>
            <div class="col-md-8">
                <input type="text" name="deposit" class="form-control deposit" value="{{ $invoice->deposit }}">
            </div>
        </div>
    </div>  

    <div class="col-md-4 clearfix">
        <div class="form-group clearfix">
            <label class="col-md-4">Invoice # <i class="fa fa-asterisk text-danger"></i></label>
            <div class="col-md-8">
                <input type="text" disabled="disabled" class="form-control disabled" value="{{ $invoice->invoice_no }}">
                <input type="hidden" name="invoice_no" value="{{ $invoice->invoice_no }}">
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-4">PO # </label>
            <div class="col-md-8">
                <input type="text" name="po_no" class="form-control" value="{{ $invoice->po_no }}">
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-4">Payment Terms <i class="fa fa-asterisk text-danger"></i></label>
            <div class="col-md-8">
                <input type="text" class="form-control payment-terms" disabled="disabled" value="{{ $invoice->payment_terms }}">
                <input type="hidden" name="payment_terms" class="payment-terms" value="{{ $invoice->payment_terms }}">
            </div>
        </div>

    </div>  

    <div class="col-md-12">
        <h4>Invoice Items</h4>
        <hr class="horizonal-line-thick">
    </div>
    <div class="clearfix add-item-to-form p-t-15">
        <div class="col-md-4 clearfix">
            <div class="form-group-inline">
                <label>Item</label>
                <input type="text" class="form-control item_name">
            </div>
            
            <div class="form-btn-group-inline">
                <label class="f-s-18 p-t-10">Optional <input type="checkbox" class="optional"> </label>
            </div>

        </div>
        <div class="col-md-4 clearfix">
                <label>Description <i class="fa fa-asterisk text-danger"></i></label>
            <textarea class="form-control description" placeholder="Item Description" rows="5"></textarea>
        </div>
        <div class="col-md-4">
            <div class="form-group-inline">
                <label>Quantity <i class="fa fa-asterisk text-danger"></i></label>
                <input min="1" type="number" class="form-control quantity">
            </div>

            <div class="form-group-inline">
                <label>Unit Price <i class="fa fa-asterisk text-danger"></i></label>
                <input type="text" class="form-control unit-price">
            </div>

            <div class="form-btn-group-inline p-t-15 action-btn-group action-add">
                <button class="btn btn-primary add-item" type="button"> <i class="fa fa-plus-circle"></i> Add Item</button>
            </div>

        </div>

        <div class="pull-left">
            <p class="f-s-15"> <span class="text-danger"> Note: </span> You Can <em class="text-primary"> Drag and Drop </em> items to rearrange items </p>
        </div>
        
    </div>

    <div class="col-md-12 bg-white m-b-15">
        <div class="table-responsive">
            <table class="table">
                <thead class="bg-success">
                    <tr>
                        <th class="width-20"></th>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Price</th>
                        <th class="width-20"></th>
                    </tr>
                </thead>
                <tbody class="invoice-items">
                    @if($invoice)
                        @foreach($invoice->items->sortBy('list_order') as $item)
                            <tr>
                                <td> 
                                    <button type='button' class='btn btn-xs btn-hover'> <i class='fa fa-arrows-v'></i> </button>
                                    <span class="hidden"> 
                                        <input type='hidden' name='quantity[]' value="{{ $item->quantity }}" >
                                        <input type='hidden' name='uprice[]' value="{{ $item->uprice }}" >
                                        <input type='hidden' name='price[]' value="{{ $item->price }}" >
                                        <input type='hidden' name='optional[]' value="{{ $item->optional }}" >
                                        <input type='hidden' name='name[]' value="{{ $item->name }}" >
                                        <textarea name='description[]' class='hidden'>{{ $item->description }}</textarea>
                                    </span>
                                </td> 
                                <td> {{ $item->name }} </td>
                                <td> {{ $item->description }} </td>
                                <td> {{ number_format($item->quantity, 0)  }} </td>
                                <td> <span class='invoice-currency'>{{ $invoice->currency ? $invoice->currency->symbol : '' }}</span> {{ number_format($item->uprice, 2) }} </td>
                                <td> @if($item->optional) Optional @else <span class='invoice-currency'>{{ $invoice->currency ? $invoice->currency->symbol : '' }}</span> {{ number_format($item->price, 2) }} @endif </td>
                                <td>
                                    <button type='button' class='btn btn-xs btn-danger remove-item btn-hover'> <i class='fa fa-minus'></i> </button> 
                                </td>

                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>  

            <hr style="background-color: #51BB8D; height: 3px;">

            <table class="table">
                <tbody class="invoice-summary">
                    <tr>
                        <th></th>
                        <td></td>
                        <th class="f-s-16 text-right">Sub Total</th>
                        <td class="f-s-16 text-right"> <span class="invoice-currency">{{ $invoice->currency ? $invoice->currency->symbol  : '' }}</span> <span class="sub-total">{{ number_format( $invoice->sub_total, 2) }}</span> </td>
                    </tr>
                    <tr>
                        <th class="width-300">
                            <select class="form-control vat-type" name="vat_type">
                                <option {{ $invoice->vat_type == 'percentage' ? 'selected' : '' }} value="percentage" selected="selected">Percentage %</option>
                                <option {{ $invoice->vat_type == 'flat' ? 'selected' : '' }} value="flat"> Amount </option>
                            </select>
                        </th>
                        <td class="width-200"><input type="text" name="vat_rate" value="5" class="form-control vat-val" ></td>
                        <th class="f-s-16 text-right">Vat</th>
                        <td class="f-s-16 text-right"> <span class="invoice-currency">{{ $invoice->currency ? $invoice->currency->symbol  : '' }}</span> <span class="vat-total">{{ $invoice->vat }}</span></td>
                    </tr>
                    <tr>
                        <th class="width-300">
                            <select class="form-control discount-type" name="discount_type">
                                <option {{ $invoice->discount_type == 'percentage' ? 'selected' : '' }} value="percentage" selected="selected">Percentage %</option>
                                <option {{ $invoice->discount_type == 'flat' ? 'selected' : '' }} value="flat">Amount</option>
                            </select>
                        </th>
                        <td class="width-200"><input type="text" name="discount_rate" value="{{ $invoice->discount_rate }}" class="form-control discount-val" ></td>
                        <th class="f-s-16 text-right">Discount</th>
                        <td class="f-s-16 text-right"> <span class="invoice-currency">{{ $invoice->currency ? $invoice->currency->symbol  : '' }}</span> <span class="discount-total">{{ $invoice->discount }}</span></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td></td>
                        <th class="f-s-16 text-right">Total</th>
                        <td class="f-s-16 f-w-600 text-right"> <span class="invoice-currency">{{ $invoice->currency ? $invoice->currency->symbol  : '' }}</span> <span class="grant-total">{{ number_format($invoice->grand_total, 2) }}</span></td>
                    </tr>
                    
                    <tr class="partial-payment hidden">
                        <th></th>
                        <td></td>
                        <th class="f-s-16 text-right">Paid To Date</th>
                        <td class="f-s-16 f-w-600 text-right"> <span class="invoice-currency">{{ $invoice->currency ? $invoice->currency->symbol  : '' }}</span> <span class="deposit-total">{{ number_format($invoice->deposit, 2) }}</span></td>
                    </tr>
                    <tr class="partial-payment hidden">
                        <th></th>
                        <td></td>
                        <th class="f-s-16 text-right">Balance Due</th>
                        <td class="f-s-16 f-w-600 text-right"> <span class="invoice-currency">{{ $invoice->currency ? $invoice->currency->symbol  : '' }}</span> <span class="balance-due">{{ number_format($invoice->balance_due, 2) }}</span></td>
                    </tr>

                </tbody>
            </table>         
        </div>

    </div>
    <div class="col-md-12">
        <h4>Invoice Addition Information</h4>
        <hr class="horizonal-line-thick">
    </div>
    <div class="col-md-12">
       <ul class="nav nav-tabs nav-theme ">
            <li class="active"><a href="#nav-terms" data-toggle="tab"> Terms & Condition </a></li>
            <li><a href="#nav-public_notes" data-toggle="tab"> Public Notes </a></li>
            <li><a href="#nav-private_notes" data-toggle="tab"> Private Notes </a></li>
            <li><a href="#nav-footer" data-toggle="tab"> Footer </a></li>
        </ul>
        <div class="tab-content bg-silver">
            <div class="tab-pane fade clearfix active in" id="nav-terms">                 
            
                <textarea name="terms" rows="5" class="form-control">{{ $invoice->terms }}</textarea>            

            </div>
            
            <div class="tab-pane fade clearfix" id="nav-public_notes">                 

                <textarea name="public_note" rows="5" class="form-control">{{ $invoice->public_note }}</textarea>            

            </div>

            <div class="tab-pane fade clearfix" id="nav-private_notes">                 
                
                <textarea name="private_note" rows="5" class="form-control">{{ $invoice->private_note }}</textarea>            
            
            </div>
            <div class="tab-pane fade clearfix" id="nav-footer">                 

                <textarea name="footer" rows="5" class="form-control">{{ $invoice->footer }}</textarea>                        
            </div>

        </div>  
    </div>


     <div class="form-group bg-white clearfix p-15 m-r-0 m-l-0 m-t-15 save-opportunity">
        
        <div class="pull-right" >
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('tenant.crm.quote.clone.invoice', [$tenant->domain, $invoice->id]) }}">Clone To Quote</a></li>
                    <li><a href="{{ route('tenant.crm.invoice.clone', [$tenant->domain, $invoice->id]) }}">Clone To Invoice</a></li>
                    <li><a href="{{ route('tenant.crm.invoice.history', [$tenant->domain, $invoice->id]) }}">Invoice History</a></li>
                    <li role="separator" class="divider"></li>
                    @if($invoice->status < 1)
                    <li><a href="javascript:;" class="change-status" data-status='2' data-invoice_id="{{ $invoice->id }}">Mark Sent</a></li>
                    @endif
                    @if($invoice->status < 3)
                    <li><a href="javascript:;" class="change-status" data-status='3' data-invoice_id="{{ $invoice->id }}">Mark Paid</a></li>
                    @endif
                    <li><a target="_blank" href="{{ asset('storage/'.$invoice->pdf_path) }}">Download Invoice</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="javascript:;" data-toggle="modal" data-target=".delete-invoice-modal">Delete Invoice</a></li>

                </ul>
            </div> &nbsp; &nbsp;
            <a class="btn btn-default" href="{{ asset('storage/'.$invoice->pdf_path) }}"> <i class="fa fa-download"></i> Download </a> &nbsp; &nbsp;

            @if($invoice->status < 3)
            <button class="btn btn-primary" type="submit"> <i class="fa fa-save"></i> Save Invoice </button>
            &nbsp; &nbsp;
            @endif
            
        </div>
    </div> 

</form>
