<div class="table-responsive bg-white p-5">
    <table class="table table-condensed table-hover table-striped">
        <thead class="bg-success">
            <tr>

                <th>S/N</th>
                <th>Title</th>
                <th>Subject</th>
                <th>Priority</th>
                <th>Severity</th>
                <th>Status</th>
                <th>Start Time</th>
                <th>Due Time</th>
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
                    <td>{{ $ticket->creator ? $ticket->creator->name : 'N/A' }}</td>
                    <td>
                        @if(Gate::check('tenant.ticket.read'))
                            <a class="btn btn-sm btn-info btn-xs" href="{{ route('tenant.ticket.show', [$tenant->domain, $ticket->id]) }}" > Details </a>
                        @endif
                    </td>
                </tr>

            @endforeach
        </tbody>
    </table>
    {!! $tickets->appends(request()->query())->links() !!}
</div>