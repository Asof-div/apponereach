<div class="table-responsive">
	
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>S/N</th>
				<th>TITLE</th>
				<th>NUMBER</th>
				<th>VOICEMAIL PROMPT</th>
				<th>EMAIL</th>
				<th>SEND VOICEMAIL TO MAIL</th>
				<th>LEAVE A COPY ON PORTAL</th>
				<th>DELETE</th>
			</tr>
		</thead>
		<tbody>
			@forelse($inboxes as $index => $inbox)
                <tr>
                	<td>{{ $index + 1 }}</td>
                	<td>
                		<a href="{{ route('tenant.media-service.inbox.show', [$tenant->domain, $inbox->user] ) }}">{{ $inbox->title }}
                		</a>
                	</td>
                	<td>{{ $inbox->user }}</td>
                	<td></td>
                	<td>{{ $inbox->email }}</td>
                	<td>{{ $inbox->send_voicemail_to_mail ? "YES" : "NO" }}</td>
                	<td>{{ $inbox->leave_copy_on_portal ? "YES" : "NO" }}</td>
                	
                    <td><button class="btn btn-sm btn-warning" data-toggle="modal" data-target=".delete-call-flow-modal" data-route_id="{{ $inbox->id }}"> <i class="fa fa-trash"></i> </button></td>
                </tr>
            @empty
                <tr> <td colspan="7" > No Voicemail Box Created. </td> </tr>
            @endforelse
		</tbody>
	</table>

</div>