<div class="table-responsive">
	
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>S/N</th>
				<th>TITLE</th>
				<th>START TIME</th>
				<th>END TIME</th>
				<th>STRATEGY</th>
				<th>DELETE</th>
			</tr>
		</thead>
		<tbody>
			@forelse($timers as $index => $timer)
                <tr>
                	<td>{{ $index + 1 }}</td>
                	<td>
                		<a href="{{ route('tenant.media-service.timer.show', [$tenant->domain, $timer->id] ) }}">{{ $timer->title }}
                		</a>
                	</td>
                	<td>{{ $timer->start_time }}</td>
                	<td>{{ $timer->end_time }}</td>
                	<td>{{ $timer->strategy }}</td>
                    <td><button class="btn btn-sm btn-warning" data-toggle="modal" data-target=".delete-timer-modal" data-timer_id="{{ $timer->id }}"> <i class="fa fa-trash"></i> </button></td>
                </tr>
            @empty
                <tr> <td colspan="7" > No Time Scheduler Created. </td> </tr>
            @endforelse
		</tbody>
	</table>

</div>
