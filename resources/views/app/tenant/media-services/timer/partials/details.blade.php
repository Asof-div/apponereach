<div class="panel panel-with-tabs clearfix p-t-20 p-r-20 p-l-20 p-b-15" >
	<h4 class="text-primary">TIME SCHEDULE DETAILS  <span class="pull-right"> <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-backdrop="static" data-target=".delete-timer-modal" > <i class="fa fa-trash"></i></button> </span></h4>
	<div class="table-responsive">
		<table class="table">
			<tbody>
				<tr>
					<th>TITLE</th>
					<td>{{ $timer->title }}</td>
					<th>DAYS</th>
					<td>{{ $timer->strategy }}</td>
				</tr>
				<tr>
					<th>START TIME </th>
					<td>{{ $timer->start_time }}</td>
					<th>END TIME</th>
					<td>{{ $timer->end_time }}</td>
				</tr>
			</tbody>
		</table>
	</div>

</div>


