<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-title">{{ $call_route->title }}</div>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			
			<table class="table">
				
				<tr>
					<th>Title</th>
					<td>{{ $call_route->title }}</td>
				</tr>
				<tr>
					<th>Period</th>
					<td>{{ $call_route->call_period }}  :-  {{ $route->start_time .' - '.$route->end_time }}</td>
				</tr>
				<tr>
					<th>Priority</th>
					<td>{{ $call_route->priority }}</td>
				</tr>
				<tr>
					<th>DAYS</th>
					<td>{{ $call_route->days_of_week }}</td>	
				</tr>
				<tr>
					<th>Greeting Message </th>
					<td>{!! $call_route->is_greeting_tts ? nl2br($call_route->greeting_tts) : $call_route->welcome->title !!}</td>
				</tr>

				<tr>
					<th colspan="2">Destinations</th>
				</tr>
				@foreach($call_route->actions as $index => $action)
					<tr>
						<th>{{ $action->destination }}</th>
						<th> <div id="range_{{ $action->id }}"> </div> </th>
					</tr>
				@endforeach
			</table>

		</div>
	</div>
</div>