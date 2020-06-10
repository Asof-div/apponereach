<div class="table-responsive bg-white p-10">
    <table class="table table-condensed table-hover table-striped">
        <thead>
            <tr>

                <th>S/N</th>
                <th>Name</th>
                <th>Label</th>
                <th>Initial Response Time</th>
                <th>Expected Resolution Time</th>
                <th>Escalation Interval Time</th>
                <th>Priority</th>
                <th>Severity</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
            @foreach($incidents as $index => $incident)

                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $incident->name }}</td>
                    <td>{{ $incident->label }}</td>
                    <td>{{ $incident->initial_response_time }}</td>
                    <td>{{ $incident->expected_resolution_time }}</td>
                    <td>{{ $incident->escalation_interval_time }}</td>
                    <td>{{ $incident->priority }}</td>
                    <td>{{ $incident->severity }}</td>
                    <td>
                        @if(Gate::check('user.update'))
                            <a class="btn btn-sm btn-info btn-xs" href="{{ route('operator.incident.show', $incident->id) }}" > Details </a>
                        @endif
                    </td>
                </tr>

            @endforeach
        </tbody>
    </table>
</div>