<div class="table-responsive">
	
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>S/N</th>
				<th>TITLE</th>
				<th>GREETING</th>
				<th>RECORD</th>
				<th>VOICEMAIL</th>
				<th>DESTINATION TYPE</th>
				<th>DESTINATION</th>
				<th>RING TIME</th>
				<th>DELETE</th>
			</tr>
		</thead>
		<tbody>
			@forelse($call_routes as $index => $route)
                <tr>
                	<td>{{ $index + 1 }}</td>
                	<td>{{ $route->title }}</td>
                	<td>{!! $route->is_greeting_tts ? nl2br($route->greeting_tts) : $route->welcome->title !!}</td>
                	<td>{{ $route->record ? "YES" : "NO" }}</td>
                	<td>{{ $route->voicemail ? "YES" : "NO" }}</td>
                	<td>{{ ucfirst($route->action) }}</td>
                	<td>{{ $route->value }}</td>
                	<td>{{ $route->ring_time }}</td>
                    <td>
                    	<button class="btn btn-sm btn-primary" data-backdrop="static" data-toggle="modal" data-target=".edit-call-flow-modal" data-route_id="{{ $route->id }}" data-record="{{ $route->record }}" data-voicemail="{{ $route->voicemail }}" data-ring_time="{{ $route->ring_time }}" data-title="{{ $route->title }}" data-module="{{ $route->dest_type }}" data-module_id="{{ $route->module_id }}" data-action="{{ $route->action }}" data-value="{{ $route->value }}" data-greeting="{{ $route->greeting_value() }}"> <i class="fa fa-edit"></i> </button> |
                    	<button class="btn btn-sm btn-warning" data-toggle="modal" data-target=".delete-call-flow-modal" data-route_id="{{ $route->id }}"> <i class="fa fa-trash"></i> </button>
                    </td>
                </tr>
            @empty
                <tr> <td colspan="7" > No Call Flow Template Created. </td> </tr>
            @endforelse
		</tbody>
	</table>

</div>