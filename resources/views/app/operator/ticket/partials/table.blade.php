<div class="table-responsive bg-white p-10">
    <table class="table table-condensed table-hover table-striped">
        <thead>
            <tr>

                <th>S/N</th>
                <th>Title</th>
                <th>Subject</th>
                <th>Priority</th>
                <th>Severity</th>
                <th>Status</th>
                <th>Start Time</th>
                <th>Due Time</th>
                <th>Raised By</th>
                <th>Creator</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $index => $ticket)

                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>{{ $ticket->priority }}</td>
                    <td>{{ $ticket->severity }}</td>
                    <td>{{ $ticket->status }}</td>
                    <td>{{ $ticket->start_date }}</td>
                    <td>{{ $ticket->due_date }}</td>
                    <td>{{ $ticket->account }}</td>
                    <td>{{ $ticket->creator ? $ticket->creator->name : 'N/A' }}</td>
                    <td>
                        @if(Gate::check('ticket.read') && strtolower($ticket->status) == 'unassigned' )
                            <a class="btn btn-sm btn-default btn-xs" href="javascript:;" data-toggle="modal" data-target="#assign_ticket_modal" data-ticket_id="{{ $ticket->id }}" > <i class="fa fa-hand-o-right"></i> Assign </a>
                        @endif
                        @if(Gate::check('ticket.read'))
                            <a class="btn btn-sm btn-info btn-xs" href="{{ route('operator.ticket.show', $ticket->id) }}" > Details </a>
                        @endif
                    </td>
                </tr>

            @endforeach
        </tbody>
    </table>
    {!! $tickets->appends(request()->query())->links() !!}

</div>