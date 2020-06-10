

<form id="edit_quote_form" class="form-horizontal" name="13" action="" enctype='multipart/form-data' method='post'>
    {{ csrf_field() }}
    <div class="col-md-12">
        <h4 class="clearfix">Quote Information <span class="pull-right"> {!! $quote->status() !!} </span> </h4>
        <hr class="horizonal-line-thick">
    </div>
    <input type="hidden" name="quote_id" value="{{ $quote->id }}">
    <input type="hidden" name="opportunity_id" value="{{ $quote->opportunity_id }}">
    <input type="hidden" name="currency_id" value="{{ $quote->currency_id }}">
    <input type="hidden" name="account" value="{{ $quote->account_id }}">

    <div class="col-md-12 ">
        <div class="form-group">
            <label class="col-md-4">Title </label>
            <div class="col-md-8">
                <input type="text" name="title" class="form-control" value="{{ $quote->title }}">
            </div>
        </div>
    </div>  

    <div class="col-md-4 ">
        <div class="form-group">
            <label class="col-md-4">Client <i class="fa fa-asterisk text-danger"></i></label>
            <div class="col-md-8">

                <div class="account-display {{ $account ? '' : 'hidden' }}">
                    <h4> {{ $account->name }} </h4>
                    <a target="blank" href="{{ route('tenant.crm.account.show', [$tenant->domain, $quote->account->name]) }}"> View Client </a>
                </div>

                <br>
                <div class="contact-list">
                    @foreach($account->contacts as $contact)
                        <div> <label><input name='contacts[]' {{ $quote->hasContact($contact->id) ? 'checked' : '' }} value="{{ $contact->id }}" type='checkbox' > 
                            {{ $contact->name }} -- {{ $contact->email }} 
                            </label> </div>
                    @endforeach 
                </div>
            </div>
        </div>
    </div>  

    <div class="col-md-4 overflow-x-auto">
        <div class="form-group clearfix">
            <label class="col-md-4">Quote Date <i class="fa fa-asterisk text-danger"></i></label>
            <div class="col-md-8">
                <input type="text" name="quote_date" class="datepicker form-control" value="{{ $quote->quote_date }}">
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-4">Valid Until </label>
            <div class="col-md-8">
                <input type="text" name="expiration_date" class="datepicker form-control" value="{{ $quote->expiration_date }}">
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-4">Partial/Depos </label>
            <div class="col-md-8">
                <input type="text" name="deposit" class="form-control deposit" value="{{ $quote->deposit }}">
            </div>
        </div>
    </div>  

    <div class="col-md-4 clearfix">
        <div class="form-group clearfix">
            <label class="col-md-4">Quote # <i class="fa fa-asterisk text-danger"></i></label>
            <div class="col-md-8">
                <input type="text" disabled="disabled" class="form-control disabled" value="{{ $quote->quote_no }}">
                <input type="hidden" name="quote_no" value="{{ $quote->quote_no }}">
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-4">PO # </label>
            <div class="col-md-8">
                <input type="text" name="po_no" class="form-control" value="{{ $quote->po_no }}">
            </div>
        </div>
        <div class="form-group clearfix">
            <label class="col-md-4">Payment Terms <i class="fa fa-asterisk text-danger"></i></label>
            <div class="col-md-8">
                <input type="text" class="form-control payment-terms" disabled="disabled" value="{{ $quote->payment_terms }}">
                <input type="hidden" name="payment_terms" class="payment-terms" value="{{ $quote->payment_terms }}">
            </div>
        </div>

    </div>  

    <div class="col-md-12">
        <h4>Quote Items</h4>
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
                <tbody class="quote-items">
                    @if($quote)
                        @foreach($quote->items->sortBy('list_order') as $item)
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
                                <td> <span class='quote-currency'>{{ $quote->currency ? $quote->currency->symbol : '' }}</span> {{ number_format($item->uprice, 2) }} </td>
                                <td> @if($item->optional) Optional @else <span class='quote-currency'>{{ $quote->currency ? $quote->currency->symbol : '' }}</span> {{ number_format($item->price, 2) }} @endif </td>
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
                <tbody class="quote-summary">
                    <tr>
                        <th></th>
                        <td></td>
                        <th class="f-s-16 text-right">Sub Total</th>
                        <td class="f-s-16 text-right"> <span class="quote-currency">{{ $quote->currency ? $quote->currency->symbol  : '' }}</span> <span class="sub-total">{{ number_format( $quote->sub_total, 2) }}</span> </td>
                    </tr>
                    <tr>
                        <th class="width-300">
                            <select class="form-control vat-type" name="vat_type">
                                <option {{ $quote->vat_type == 'percentage' ? 'selected' : '' }} value="percentage" selected="selected">Percentage %</option>
                                <option {{ $quote->vat_type == 'flat' ? 'selected' : '' }} value="flat"> Amount </option>
                            </select>
                        </th>
                        <td class="width-200"><input type="text" name="vat_rate" value="5" class="form-control vat-val" ></td>
                        <th class="f-s-16 text-right">Vat</th>
                        <td class="f-s-16 text-right"> <span class="quote-currency">{{ $quote->currency ? $quote->currency->symbol  : '' }}</span> <span class="vat-total">{{ $quote->vat }}</span></td>
                    </tr>
                    <tr>
                        <th class="width-300">
                            <select class="form-control discount-type" name="discount_type">
                                <option {{ $quote->discount_type == 'percentage' ? 'selected' : '' }} value="percentage" selected="selected">Percentage %</option>
                                <option {{ $quote->discount_type == 'flat' ? 'selected' : '' }} value="flat">Amount</option>
                            </select>
                        </th>
                        <td class="width-200"><input type="text" name="discount_rate" value="{{ $quote->discount_rate }}" class="form-control discount-val" ></td>
                        <th class="f-s-16 text-right">Discount</th>
                        <td class="f-s-16 text-right"> <span class="quote-currency">{{ $quote->currency ? $quote->currency->symbol  : '' }}</span> <span class="discount-total">{{ $quote->discount }}</span></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td></td>
                        <th class="f-s-16 text-right">Total</th>
                        <td class="f-s-16 f-w-600 text-right"> <span class="quote-currency">{{ $quote->currency ? $quote->currency->symbol  : '' }}</span> <span class="grant-total">{{ number_format($quote->grand_total, 2) }}</span></td>
                    </tr>
                    
                    <tr class="partial-payment hidden">
                        <th></th>
                        <td></td>
                        <th class="f-s-16 text-right">Paid To Date</th>
                        <td class="f-s-16 f-w-600 text-right"> <span class="quote-currency">{{ $quote->currency ? $quote->currency->symbol  : '' }}</span> <span class="deposit-total">{{ number_format($quote->deposit, 2) }}</span></td>
                    </tr>
                    <tr class="partial-payment hidden">
                        <th></th>
                        <td></td>
                        <th class="f-s-16 text-right">Balance Due</th>
                        <td class="f-s-16 f-w-600 text-right"> <span class="quote-currency">{{ $quote->currency ? $quote->currency->symbol  : '' }}</span> <span class="balance-due">{{ number_format($quote->balance_due, 2) }}</span></td>
                    </tr>

                </tbody>
            </table>         
        </div>

    </div>
    <div class="col-md-12">
        <h4>Quote Addition Information</h4>
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
            
                <textarea name="terms" rows="5" class="form-control">{{ $quote->terms }}</textarea>            

            </div>
            
            <div class="tab-pane fade clearfix" id="nav-public_notes">                 

                <textarea name="public_note" rows="5" class="form-control">{{ $quote->public_note }}</textarea>            

            </div>

            <div class="tab-pane fade clearfix" id="nav-private_notes">                 
                
                <textarea name="private_note" rows="5" class="form-control">{{ $quote->private_note }}</textarea>            
            
            </div>
            <div class="tab-pane fade clearfix" id="nav-footer">                 

                <textarea name="footer" rows="5" class="form-control">{{ $quote->footer }}</textarea>                        
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
                    <li><a href="#">Clone To Invoice</a></li>
                    <li><a href="#">Clone To Quote</a></li>
                    <li><a href="{{ route('tenant.crm.quote.history', [$tenant->domain, $quote->id]) }}">Quote History</a></li>
                    <li role="separator" class="divider"></li>
                    @if($quote->status < 2)
                        <li><a href="javascript:;" class="change-status" data-status='2' data-quote_id="{{ $quote->id }}">Mark Sent</a></li>
                    @endif
                    @if($quote->status < 3)
                        <li><a href="javascript:;" class="convert-quote" data-quote_id="{{ $quote->id }}">Convert To Invoice</a></li>
                    @endif
                    <li><a target="_blank" href="{{ asset('storage/'.$quote->pdf_path) }}">Download Quote</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="javascript:;" data-toggle="modal" data-target=".delete-quote-modal" >Delete Quote</a></li>

                </ul>
            </div> &nbsp; &nbsp;
            <a target="_blank" class="btn btn-default" href="{{ asset('storage/'.$quote->pdf_path) }}"> <i class="fa fa-download"></i> Download </a> &nbsp; &nbsp;
            <button class="btn btn-primary" type="submit"> <i class="fa fa-save"></i> Save Quote </button>
            &nbsp; &nbsp;
            
        </div>
    </div> 

</form>