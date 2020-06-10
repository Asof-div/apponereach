<div class="table-responsive">

    <table class="table table-hovered table-striped">
    
        <thead class="bg-success">
            <tr>
                <th class="width-20">S/N</th>
                <th>Invoice No#</th>
                <th>Title</th>
                <th>Account</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Valid Until</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach($invoices as $index => $invoice)
                <tr>
                    <td>{{$index+1}}</td>
                    <td> <a href="{{ route('tenant.crm.invoice.edit', [$tenant->domain, $invoice->id]) }}"> {{$invoice->invoice_no}} </a> </td>
                    <td> {{$invoice->title}} </td>
                    <td> <a href="{{ route('tenant.crm.account.show', [$tenant->domain, $invoice->account->name]) }}"> {{$invoice->account->name}} </a> </td>
                    <td> {{ $invoice->invoice_date }} </td>
                    <td> {{ $invoice->currency ? $invoice->currency->symbol : '' }} {{ number_format($invoice->grand_total, 2) }} </td>
                    <td> {{ $invoice->expiration_date }} </td>
                    <td> {!! $invoice->status() !!} </td>
                    <td>
                        <!-- Single button -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('tenant.crm.invoice.clone', [$tenant->domain, $invoice->id]) }}">Clone To Invoice</a></li>
                                <li><a href="{{ route('tenant.crm.quote.clone.invoice', [$tenant->domain, $invoice->id]) }}">Clone To Quote</a></li>
                                <li><a href="{{ route('tenant.crm.invoice.history', [$tenant->domain, $invoice->id]) }}">Invoice History</a></li>
                                <li role="separator" class="divider"></li>
                                @if($invoice->status < 2)
                                    <li><a href="javascript:;" class="change-status" data-status='3' data-invoice_id="{{ $invoice->id }}">Mark Sent</a></li>
                                @endif
                                @if($invoice->status < 3)
                                    <li><a href="javascript:;" class="change-status" data-status='3' data-invoice_id="{{ $invoice->id }}">Mark Paid</a></li>
                                @endif
                                <li><a target="_blank" href="{{ asset('storage/'.$invoice->pdf_path) }}">Download Invoice</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="javascrript:;" data-toggle="modal" data-target=".delete-invoice-modal" data-invoice_id="{{ $invoice->id }}">Delete Invoice</a></li>

                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    
    </table>

    {{ $invoices->links() }}

</div>