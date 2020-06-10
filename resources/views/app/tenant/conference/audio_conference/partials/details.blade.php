<div class="table-responsive">
	<table class="table">
		<tbody>
			<tr>
				<th> Name</th>
				<td>{{ $conference->bridge_name }}</td>
			</tr>
			<tr>
				<th>Number</th>
            	<td> {{ $conference->number }} </td>
        	</tr>
        	<tr>
				<th>Guest Pin</th>
            	<td> {{ $conference->guest_pin }} </td>
        	</tr>
        	<tr>
				<th>Moderator Pin</th>
            	<td> {{ $conference->admin_pin }} </td>
        	</tr>
        	<tr>
        		<th>Record</th>
                <td> {{ $conference->record ? 'YES' : 'NO' }} </td>
            </tr>
            <tr>
            	<th>Conference Begins When An Admin Arrives </th>
                <td> {{ $conference->wait_for_admin ? 'YES' : 'NO' }} </td>
            </tr>
            <tr>
            	<th>Announce When user join/leave Confence </th>
                <td> {{ $conference->annouce_join_leave ? 'YES' : 'NO' }} </td>
			</tr>

		</tbody>
	</table>
</div>
