<div class="col-md-12 clearfix">
	
	<div class="f-s-20">
		Auto Attendant Call Flow will considered as first choice if a matching condition is found. 
	</div>

	<div class="table-responsive">
		<table class="table">
			<tr>
				<td>
					<input type="text" class="form-control auto-attendant-tag" placeholder="Enter Tag Name">
				</td>
				<td>
					<select class="form-control auto-attendant-timer">
						<option value=""> &dash; &dash; &dash; Select A Time Schedule &dash; &dash; &dash; </option>
						@forelse($timers as $timer)
							<option value="{{ $timer->id }}" data-title="{{ $timer->title }}" data-start="{{ $timer->start_time }}" data-end="{{ $timer->end_time }}" data-strategy="{{ $timer->strategy }}" > {{ $timer->title }} </option>
						@empty

						@endforelse
					</select>
				</td>
				<td>
					<select class="form-control auto-attendant-route">
						<option value=""> &dash; &dash; &dash; Select A Call Flow &dash; &dash; &dash; </option>
						@forelse($call_routes as $route)
							<option value="{{ $route->id }}" data-title="{{ $route->title }}" data-destination="{{ $route->value }}" data-destination_type="{{ $route->action }}" data-ring="{{ $route->ring_time }}" data-voicemail="{{ $route->voicemail ? 'YES' : 'NO' }}" data-record="{{ $route->record ? 'YES' : 'NO' }}"  > {{ $route->title }} </option>
						@empty

						@endforelse
					</select>
				</td>
				<td>
					<button type="button" class="btn btn-success add-auto-attendant"> <i class="fa fa-plus"></i> Add Auto Attendant</button>
				</td>
			</tr>
		</table>
	</div>



	<div class="table-responsive">
		<form action="" name="13" method="post" id="auto_attendant_reorder_form">

			{{ csrf_field() }}
		    <input type="hidden" name="pilot_line" value="{{ $pilot_line->id }}">
		    <input type="hidden" name="pilot_number" value="{{ $pilot_line->number }}">
			<input type="hidden" name="configured" value="{{ count($pilot_line->auto_attendants) }}" id="config_status">	

			<table class="table table-hover ">
				<thead>
					<tr>
						<th> Tag </th>
						<th> Time Label </th>
						<th> Active Time </th>
						<th> Active Period </th>
						<th> Call Flow Label </th>
						<th> Recording </th>
						<th> Voicemail </th>
						<th> Call Destination Type </th>
						<th> Call Destination </th>
						<th> </th>
					</tr>
				</thead>
				<tbody class="reorder-list" id="">
					@forelse($pilot_line->auto_attendants as $index => $attendant)
						<tr class="f-s-15">
							<td> {{ $attendant->title }} <input type="hidden" name="titles[]" value="{{ $attendant->title }}" /> </td>  
	                        <td>{{ $attendant->timer->title }} <input type="hidden" name="timers[]" value="{{ $attendant->timer->id }}" />  </td>
	                        <td> {{ $attendant->timer->start_time .' - '. $attendant->timer->end_time }} </td>
	                        <td> {{ $attendant->timer->strategy }} </td>
	                        <td> {{ $attendant->flow->title }} <input type="hidden" name="routes[]" value="{{ $attendant->flow->id }}" /> </td>
	                        <td> {{ $attendant->flow->voicemail ? 'YES' : 'NO' }} </td>
	                        <td> {{ $attendant->flow->record ? 'YES' : 'NO' }} </td>
	                        <td> {{ $attendant->flow->action }} </td>
	                        <td> {{ $attendant->flow->value }} </td>
	                        <td> <button type="button" class="btn btn-xs btn-danger remove-auto-attendant"> <i class="fa fa-trash"> </i> </button> </td>

						</tr>
					@empty

					@endforelse
				</tbody>			
			</table>
		
			<div class="form-group bg-white clearfix p-10 m-r-25 m-l-25">
		        <div class="col-md-4 col-sm-6 col-xs-6 col-md-offset-5 col-sm-offset-4" >
			        <button type="submit" class="btn btn-primary ">Save Auto Attendant</button>
			    </div>
			</div>

		</form>
	</div>
</div>
