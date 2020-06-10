

<form id="opportunity_items_form" class="form-horizontal" name="13" action="" enctype='multipart/form-data' method='post'>

    <input type="hidden" name="opportunity_line_id" value="{{ $opportunity_line->id }}">    

    <div class="col-md-12 bg-white m-b-15 add-item-to-form">
        <div class="clearfix p-t-15">
            <div class="col-md-6 clearfix">
                <textarea class="form-control description" placeholder="Item Description" rows="5"></textarea>
            </div>
            <div class="col-md-6">
                <div class="form-group-inline">
                    <label>Quantity</label>
                    <input min="1" type="number" class="form-control quantity">
                </div>
                
                <div class="form-btn-group-inline">
                    <label class="f-s-18 p-t-10">Optional <input type="checkbox" class="optional"> </label>
                </div>

                <div class="form-group-inline">
                    <label>Unit Price</label>
                    <input type="text" class="form-control unit-price">
                </div>

                <div class="form-btn-group-inline p-t-15 action-btn-group action-add">
                    <button class="btn btn-primary add-item" type="button"> <i class="fa fa-plus-circle"></i> Add Item</button>
                </div>
                <div class="form-btn-group-inline p-t-15 action-btn-group action-update hide">
                    <button class="btn btn-success update-item" type="button"> <i class="fa fa-refresh"></i> Update Item</button>
                </div>
                <div class="form-btn-group-inline p-t-15 action-btn-group action-cancel hide">
                    <button class="btn btn-warning cancel-update-item" type="button"> <i class="fa fa-times-circle"></i> Cancel </button>
                </div>

            </div>

            <div class="pull-left">
                <p class="f-s-15"> <span class="text-danger"> Note: </span> You Can <em class="text-primary"> Drag and Drop </em> items to rearrange items </p>
            </div>
            
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th class="width-100">#</th>
                        <th>Item Description</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Price</th>

                    </tr>
                </thead>
                <tbody class="quote-items">
                    @foreach($opportunity_line->items as $item)
                        <tr>
                            <td> 
                                <button type='button' class='btn btn-xs btn-danger remove-item'> <i class='fa fa-trash'></i> </button> 
                                <button type='button' class='btn btn-xs btn-default edit-item'> <i class='fa fa-edit'></i> </button>
                                <span class="hidden"> 
                                    <input type='hidden' name='quantity[]' value="{{ $item->quantity }}" >
                                    <input type='hidden' name='uprice[]' value="{{ $item->uprice }}" >
                                    <input type='hidden' name='price[]' value="{{ $item->price }}" >
                                    <input type='hidden' name='optional[]' value="{{ $item->optional }}" >
                                    <textarea name='description[]' class='hidden'>{{ $item->description }}</textarea>
                                </span>
                            </td> 
                            <td> {{ $item->description }} </td>
                            <td> {{ number_format($item->quantity, 0)  }} </td>
                            <td> <span class='quote-currency'>{{ $opportunity_line->currency ? $opportunity_line->currency->symbol : '' }}</span> {{ number_format($item->uprice, 2) }} </td>
                            <td> @if($item->optional) Optional @else <span class='quote-currency'>{{ $opportunity_line->currency ? $opportunity_line->currency->symbol : '' }}</span> {{ number_format($item->price, 2) }} @endif </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>  

            <hr style="background-color: #51BB8D; height: 3px;">

            <table class="table">
                <tbody class="quote-summary">
                    <tr>
                        <th></th>
                        <td></td>
                        <th class="f-s-16 text-right">Sub Total</th>
                        <td class="f-s-16 text-right"> <span class="quote-currency">{{ $opportunity_line->currency ? $opportunity_line->currency->symbol  : '' }}</span> <span class="sub-total">{{ number_format( $opportunity_line->sub_total, 2) }}</span> </td>
                    </tr>
                    <tr>
                        <th class="width-300">
                            <select class="form-control vat-type" name="vat_type">
                                <option {{ $opportunity_line->vat_type == 'percentage' ? 'selected' : '' }} value="percentage" selected="selected">Percentage %</option>
                                <option {{ $opportunity_line->vat_type == 'flat' ? 'selected' : '' }} value="flat">Flat</option>
                            </select>
                        </th>
                        <td class="width-200"><input type="text" name="vat_rate" value="5" class="form-control vat-val" name="vat-val"></td>
                        <th class="f-s-16 text-right">Vat</th>
                        <td class="f-s-16 text-right"> <span class="quote-currency">{{ $opportunity_line->currency ? $opportunity_line->currency->symbol  : '' }}</span> <span class="vat-total">{{ $opportunity_line->vat }}</span></td>
                    </tr>
                    <tr>
                        <th class="width-300">
                            <select class="form-control discount-type" name="discount_type">
                                <option {{ $opportunity_line->discount_type == 'percentage' ? 'selected' : '' }} value="percentage" selected="selected">Percentage %</option>
                                <option {{ $opportunity_line->discount_type == 'flat' ? 'selected' : '' }} value="flat">Flat</option>
                            </select>
                        </th>
                        <td class="width-200"><input type="text" name="discount_rate" value="{{ $opportunity_line->discount_rate }}" class="form-control discount-val" name="discount-val"></td>
                        <th class="f-s-16 text-right">Discount</th>
                        <td class="f-s-16 text-right"> <span class="quote-currency">{{ $opportunity_line->currency ? $opportunity_line->currency->symbol  : '' }}</span> <span class="discount-total">{{ $opportunity_line->discount }}</span></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td></td>
                        <th class="f-s-16 text-right">Total</th>
                        <td class="f-s-18 f-w-600 text-right"> <span class="quote-currency">{{ $opportunity_line->currency ? $opportunity_line->currency->symbol  : '' }}</span> <span class="grant-total">{{ number_format($opportunity_line->grand_total, 2) }}</span></td>
                    </tr>
                </tbody>
            </table>         
        </div>

    </div>

     <div class="form-group bg-white clearfix p-15 m-r-0 m-l-0 m-t-15 save-opportunity">
        <div class="pull-left">
            <p class="f-s-15"> <span class="text-danger"> Note: </span> Use the <em class="text-primary"> SAVE ITEMS </em> button to save changes permanently </p>
        </div>
        <div class="pull-right" >
            <button class="btn btn-default" type="reset"> <i class="fa fa-exclamation-circle"></i> Cancel</button> &nbsp;
            <button class="btn btn-primary" type="submit"> <i class="fa fa-save"></i> Save Items </button>
        </div>
    </div> 

</form>