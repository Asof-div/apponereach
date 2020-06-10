<div class="table-responsive">

    <table class="table table-hovered table-striped">
    
        <thead class="bg-success">
            <tr>
                <th class="width-20">S/N</th>
                <th>Quote No#</th>
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
            @foreach($quotes as $index => $quote)
                <tr>
                    <td>{{$index+1}}</td>
                    <td> <a href="{{ route('tenant.crm.quote.edit', [$tenant->domain, $quote->id]) }}"> {{$quote->quote_no}} </a> </td>
                    <td> {{$quote->title}} </td>
                    <td> <a href="{{ route('tenant.crm.account.show', [$tenant->domain, $quote->account->name]) }}"> {{$quote->account->name}} </a> </td>
                    <td> {{ $quote->quote_date }} </td>
                    <td> {{ $quote->currency ? $quote->currency->symbol : '' }} {{ number_format($quote->grand_total, 2) }} </td>
                    <td> {{ $quote->expiration_date }} </td>
                    <td> {!! $quote->status() !!} </td>
                    <td>
                        <!-- Single button -->
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
                                <li><a href="javascript:;" data-quote_id="{{ $quote->id }}" data-toggle="modal" data-target=".delete-quote-modal" >Delete Quote</a></li>

                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    
    </table>

    {!! $quotes->links() !!}
</div>