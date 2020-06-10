<div class="row bg-silver ">
	@include('app.tenant.crm.contact.partials.edit_form')
</div>	
<div class="row bg-silver p-t-10">
	
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th class="width-20"></th>
					<th>Caller </th>
					<th>Receiver </th>
					<th>Direction </th>
					<th>Duration </th>
					<th>Status </th>
					<th>Call Time </th>
					<th>Answer Time </th>
					<th>End Time </th>
				</tr>
			</thead>
			<tbody>
				@foreach($contact->cdrs() as $index => $cdr)
					<tr>
						<td>{{ $index + 1 }}</td>
						<td>{!! $cdr->matchNumber('caller') !!}</td>
						<td>{!! $cdr->matchNumber('receiver') !!}</td>
						<td>{{ $cdr->direction }}</td>
						<td>{{ $cdr->duration }}</td>
						<td>{{ $cdr->status }}</td>
						<td>{{ $cdr->start_timestamp }}</td>
						<td>{{ $cdr->answer_timestamp }}</td>
						<td>{{ $cdr->end_timestamp }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

</div>