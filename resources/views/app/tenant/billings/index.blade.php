@extends('layouts.tenant_sidebar')

@section('title')
    
    BILLING REPORT

@endsection

@section('breadcrumb')

    <li class="active"> Billing Summary </li>

@endsection

@section('content')

    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">
                        CURRENT SUBSCRIPTION 
                        <span class="pull-right">
                            <a href="{{ route('tenant.billing.subscription.show', [$tenant->domain, $subscription->id]) }}" class="btn btn-success btn-xs"> <i class="fa fa-eye"></i> details </a>
                        </span>
                    </div>
                </div>
                <div class="panel-body" style="height: 500px; clear: both; overflow: auto;">
                
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tbody class="">
                                <tr>
                                    <th>STATUS</th>
                                    <td>{!! $subscription->status() !!}</td>
                                </tr>
                                <tr>
                                    <th>PAYMENT STATUS</th>
                                    <td>{!! $subscription->payment_status() !!}</td>
                                </tr>
                                <tr>
                                    <th> NUMBER LIMIT </th>
                                    <td> {{ $subscription->package ? $subscription->package->msisdn_limit : '3' }} </td>
                                </tr>
                                <tr>
                                    <th>START DATE</th>
                                    <td>{{ $subscription->start_time->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>EXPIRATION DATE</th>
                                    <td>{{ $subscription->end_time->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>BILLING METHOD</th>
                                    <td>{{ strtoupper($subscription->billing_method) }}</td>
                                </tr>
                                <tr>
                                    <th>DURATION</th>
                                    <td>{{ $subscription->duration .' DAYS' }}</td>
                                </tr>
                                <tr>
                                    <th>PILOT LINE</th>
                                    <td>{{ $subscription->pilot_line }}</td>
                                </tr>
                                <tr>
                                    <th>PLAN</th>
                                    <td>{{ strtoupper($subscription->package->name) }}</td>
                                </tr>
                                <tr>
                                    <th>DESCRIPTION</th>
                                    <td>{{ $subscription->description }}</td>
                                </tr>
                                <tr>
                                    <th>AMOUNT</th>
                                    <td>{{ $subscription->currency.number_format($subscription->total, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>EXTRA MSISDN</th>
                                    <td>{!! $subscription->extra_msisdn !!}</td>
                                </tr>
                                
                                <tr>
                                    <th>ADDONS</th>
                                    <td>{!! $subscription->addons !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="panel-title">
                        MY ORDERS 
                        <span class="pull-right">
                            <a href="{{ route('tenant.billing.subscription.show', [$tenant->domain, $subscription->id]) }}" class="btn btn-success btn-xs"> <i class="fa fa-eye"></i> details </a>
                        </span>
                    </div>
                </div>
                <div class="panel-body" style="height: 500px; clear: both; overflow: auto;">
                    
                    <div class="table-responsive">

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>TYPE</th>
                                    <th>STATUS</th>
                                    <th>PAYMENT STATUS</th>
                                    <th>AMOUNT</th>
                                    <th>DUE DATE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscription->billings as $index => $billing)
                                    <tr>
                                        <td>{{ strtoupper($billing->billing_type) }}</td>
                                        <td>{!! $billing->status() !!}</td>
                                        <th>{!! $billing->payment_status() !!}</th>
                                        <td>{{ $billing->currency . number_format($billing->charged, 2) }}</td>
                                        <td>{{ $billing->due_date }}</td>
                                        <th><a href="{{ route('tenant.billing.order.show', [$tenant->domain, $billing->id]) }}" class="btn btn-success btn-xs"> DETAILS </a></th>
                                        
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"> No Order Found In Database. </td>
                                    </tr>
                                @endforelse
                            </tbody>    
                         
                        </table>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">
                        OUTSTANDING INVOICES 
                        <span class="pull-right">
                            <a href="{{ route('tenant.billing.subscription.show', [$tenant->domain, $subscription->id]) }}" class="btn btn-success btn-xs"> <i class="fa fa-eye"></i> details </a>
                        </span>
                    </div>
                </div>
                <div class="panel-body" style="height: 500px; clear: both; overflow: auto;">
                    
                    <div class="table-responsive">

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>TNS NO#</th>
                                    <th>DATE</th>
                                    <th>STATUS</th>
                                    <th>AMOUNT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $index => $invoice)
                                    <tr>
                                        <td>{{ strtoupper($invoice->transaction_no) }}</td>
                                        <td>{{ $invoice->created_at->format('d M Y') }}</td>
                                        <td>{!! $invoice->status() !!}</td>
                                        <td>{{ $invoice->currency . number_format($invoice->amount, 2) }}</td>
                                        <th><a href="{{ route('tenant.billing.transaction.show', [$tenant->domain, $invoice->id]) }}" class="btn btn-success btn-xs"> DETAILS </a></th>
                                        
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"> No outstanding payment. </td>
                                    </tr>
                                @endforelse
                            </tbody>    
                         
                        </table>

                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection


@section('extra-script')
    
    <script type="text/javascript">
        $mn_list = $('.sidebar ul.nav > li.nav-billing');
        $mn_list.addClass('active');
        $mn_list.find('.sub-menu > .sub-menu-billing').addClass('active');
        
    </script>



@endsection

@section('extra-css')


@endsection