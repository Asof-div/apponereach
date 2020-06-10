
<div class="table-responsive">
    
    

    <div class="col-md-12 no-p-h clearfix bg-silver m-t-md">
        
        @if( strtolower($type) == 'msisdn')
            <div class="clearfix">
                <span class="h4">MSISDN ORDER</span>
            </div>

            <div class="order-containers msisdn-order ">
                <div class="m-t-md m-b-md clearfix">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Product</span>
                            <select class="form-control addon-product" aria-describedby="basic-addon1">
                                <option value="msisdn" data-title="MSISDN SLOT" data-price="500">MSISDN SLOT , &#x20A6;500 per slot</option>
                            </select>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon2">QTY</span>
                            <input class="form-control addon-qty" type="text" aria-describedby="basic-addon2" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-block add-addon-item" >ADD ITEM</button>
                    </div>
                </div>
                <form method="post" action="#" id="msisdn_order_form">
                    <input type="hidden" name="order_type" value="msisdn">
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    <input type="hidden" name="subscription_id" value="{{ $customer->subscription->id }}">
                    <input type="hidden" name="products">
                    <div class="col-md-12 clearfix">
                        <div class="table-responsive clearfix">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th> Product </th>
                                        <th> Item </th>
                                        <th> Duration </th>
                                        <th> Amount </th>
                                        <th> Charged </th>
                                        <th> Status </th>
                                    </tr>
                                </thead>
                                <tbody class="addon-items">
                                    
                                </tbody>
                                <tfoot>
                                    <th colspan="3">Subtotal</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tfoot>
                            </table>
                        </div>

                        <div class="form-group clearfix">
                            <a href="{{ route('operator.customer.order.create', [$customer->id]) }}" class="btn btn-default" > <i class="fa fa-undo"></i> RETURN </a>
                            <span class="pull-right">
                                <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> SAVE AND CONFIRM ORDER</button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        @elseif( strtolower($type) == 'upgrade')
            <div class="clearfix">
                <span class="h4">UPGRADE ORDER</span>
            </div>

            <div class="order-containers upgrade-order ">
                <div class="m-t-md m-b-md clearfix">
                <form method="post" action="#" id="upgrade_order_form">
                    <input type="hidden" name="order_type" value="upgrade">
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                    <input type="hidden" name="products">
                    <div class="col-md-12 clearfix">
                        <div class="table-responsive clearfix">
                                
                            <div class="col-md-3">
                                <div class="panel panel-success"> 
                                    <div class="panel-heading">
                                        <span class="panel-title">Current Package</span>
                                    </div>
                                    <div class="panel-body">
                                        <ul class="cd-pricing-wrapper">
                                            <li data-type="monthly" class="is-ended is-visible">
                                                <header class="cd-pricing-header">
                                                    <h2>{{ $subscription->package->name }}</h2>
                                                    <div class="cd-price">
                                                        <span class="cd-currency">&#x20A6;</span>
                                                        <span class="cd-value">{{ number_format($subscription->package->price) }}</span>
                                                        <span class="cd-duration">mo</span>
                                                    </div>
                                                </header> 
                                                
                                            </li>
                                            <li class="clearfix overflow-text-content">
                                                {{ implode(", ", $subscription->package->addons->pluck('label')->toArray() ) }}
                                            </li>{{-- 
                                            <li>
                                                <label class="text-center width-full bg-info p-10 f-s-14">
                                                    <input type="radio" name="plan" {{ strtolower(request()->plan) == strtolower('monthly_'. $package->name) ? 'checked' : '' }} value="monthly_{{ $package->name }}">
                                                    MONTHLY
                                                </label>
                                            </li> --}}

                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="panel panel-info"> 
                                    <div class="panel-heading">
                                        <span class="panel-title"> Available Upgrade</span>
                                    </div>
                                    <div class="panel-body">
                                        @forelse($packages as $package)
                                            <div class="col-md-6"> 
                                                <ul class="cd-pricing-wrapper">
                                                    <li data-type="monthly" class="is-ended is-visible">
                                                        <header class="cd-pricing-header">
                                                            <h2>{{ $package->name }}</h2>
                                                            <div class="cd-price">
                                                                <span class="cd-currency">&#x20A6;</span>
                                                                <span class="cd-value">{{ number_format($package->price) }}</span>
                                                                <span class="cd-duration">mo</span>
                                                            </div>
                                                        </header> 
                                                        
                                                    </li>
                                                    <li class="clearfix overflow-text-content">
                                                        {{ implode(", ", $package->addons->pluck('label')->toArray() ) }}
                                                    </li>
                                                    <li>
                                                        <label class="text-center bg-info p-10 f-s-14 m-t-30" style="display: block; padding: 15px;">
                                                            <input type="radio" name="plan" value="{{ $package->id }}">
                                                            SELECT
                                                        </label>
                                                    </li>

                                                </ul>
                                            </div>
                                        @empty
                                            <div>
                                                <h4>No Upgrade Available.  <a href="{{ route('operator.customer.order.create', [$customer->id]) }}" class="btn" > <i class="fa fa-undo"></i> RETURN </a></h4>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            @if($packages->count() > 0)
                            <div class="form-group clearfix">
                                <a href="{{ route('operator.customer.order.create', [$customer->id]) }}" class="btn btn-default" > <i class="fa fa-undo"></i> RETURN </a>
                                <span class="pull-right">
                                    <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> UPGRADE ORDER</button>
                                </span>
                            </div>
                            @endif

                        </div>
                        
                    </div>
                </form>
            </div>
        

        @elseif( strtolower($type) == 'did' )


            <div class="order-containers did-order">
                <div class="m-t-md clearfix">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Product</span>
                            <select class="form-control" aria-describedby="basic-addon1">
                                <option value="local">Local </option>
                                <option value="tollfree">Total Free </option>
                                <option value="vanity">Vanity </option>
                            </select>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon2">Number</span>
                            <input class="form-control" type="text" aria-describedby="basic-addon2" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-block" >SEARCH </button>
                    </div>
                </div>
                <form method="post" action="#">
                    
                    <div class="col-md-12 form-group clearfix">
                        <a href="{{ route('operator.customer.order.create', [$customer->id]) }}" class="btn cancel-order" type="reset" > CANCEL </a>
                    </div>
                </form>
            </div>
        @else

            <div class=" height-250" style="height: 250px !important;">

                <span class="dropdown">
                    <button class="btn btn-success btn-lg" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-shopping-cart"></i> Select Order Type
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu" style="display: block; min-width: 350px; width: auto; margin-top: 30px; margin-left: 30px;">
                        <li class="m-5 border f-s-15" ><a href="{{ route('operator.customer.order.create', [$customer->id, 'msisdn']) }}"> <span class="h4"> MSISDN: Purchase additional destination number slot.</span> </a></li>
                        <li role="separator" class="divider"></li>
                        <li class="m-5 border f-s-15"><a href="{{ route('operator.customer.order.create', [$customer->id, 'upgrade']) }}"> <span class="h4"> UPGRADE: Upgrade customer subscription package. </span> </a></li>
                    </ul>
                </span>

            </div>





        @endif


        <div class="order-containers renewal-order hidden">
            <form method="post" action="#">
                

                <div class="form-group clearfix">
                    <button class="btn cancel-order" type="reset" > CANCEL </button>
                </div>
            </form>                        
        </div>

    </div>
       
</div>