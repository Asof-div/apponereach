<div class="col-md-12 clearfix">
	
	<form>
		
		<div class="form-group " style="border-bottom: 1px dashed #ccc; ">
			<label class="radio m-20">
				<input type="radio" name="strategy" class="strategy" checked="checked" value="all">
				<span class="f-s-20">24 hours Open Office</span>
				<p>Choose this option if you want incoming calls to be handles the same way all the time.</p>
			</label>
			<label class="radio m-20">
				<input type="radio" name="strategy" class="strategy" value="work">
				<span class="f-s-20">Work hours Office 8:00 - 17:00 </span>
				<p>Choose this option if you want incoming calls to be handles the same way 8:00 - 17:00 and differently when your office is closed.</p>
			</label>
			<label class="radio m-20">
				<input type="radio" name="strategy" class="strategy" value="custom">
				<span class="f-s-20">Custom Office Hours</span>
				<p>Chose this option if you want incoming calls to be handles differently when your office is closed.</p>
			</label>
		</div>

		<div class="col-md-12 strategy-box work-hours-strategy hide" style="border-bottom: 1px dashed #ccc; ">
			<span class="f-s-20">Work Hours</span> 
			<div class="table-responsive">
				<table class="table ">
					<tbody >
						<tr class="f-s-15"> 
							<td class="p-l-35 weekdays"> <span class=""> <label class="checkbox"> <input type="checkbox" checked="checked" name="" disabled="disabled"> Monday </label> </span> </td> 
							<td class="timebox"> 8:00 </td> 
							<td class="timebox"> to </td> 
							<td class="timebox"> 17:00 </td> 
							<td class="text-right "> Open </td> 
						</tr>
						<tr class="f-s-15"> 
							<td class="p-l-35"> <span> <label class="checkbox"> <input type="checkbox" checked="checked" name="" disabled="disabled"> Tuesday </label> </span> </td> 
							<td class="timebox"> 8:00 </td> 
							<td class="timebox"> to </td> 
							<td class="timebox"> 17:00 </td> 
							<td class="text-right "> Open </td> 
						</tr>
						<tr class="f-s-15"> 
							<td class="p-l-35"> <span> <label class="checkbox"> <input type="checkbox" checked="checked" name="" disabled="disabled"> Wednesday </label> </span> </td> 
							<td class="timebox"> 8:00 </td> 
							<td class="timebox"> to </td> 
							<td class="timebox"> 17:00 </td> 
							<td class="text-right "> Open </td> 
						</tr>
						<tr class="f-s-15"> 
							<td class="p-l-35"> <span> <label class="checkbox"> <input type="checkbox" checked="checked" name="" disabled="disabled"> Thursday </label> </span> </td> 
							<td class="timebox"> 8:00 </td> 
							<td class="timebox"> to </td> 
							<td class="timebox"> 17:00 </td> 
							<td class="text-right "> Open </td> 
						</tr>
						<tr class="f-s-15"> 
							<td class="p-l-35"> <span> <label class="checkbox"> <input type="checkbox" checked="checked" name="" disabled="disabled"> Friday </label> </span> </td> 
							<td class="timebox"> 8:00 </td> 
							<td class="timebox"> to </td> 
							<td class="timebox"> 17:00 </td> 
							<td class="text-right "> Open </td> 
						</tr>
						<tr class="f-s-15"> 
							<td class="p-l-35"> <span> <label class="checkbox"> <input type="checkbox" name="" disabled="disabled"> Saturday </label> </span> </td> 
							<td> </td> 
							<td> </td> 
							<td> </td> 
							<td class="text-right "> Closed </td> 
						</tr>
						<tr class="f-s-15"> 
							<td class="p-l-35"> <span> <label class="checkbox"> <input type="checkbox" name="" disabled="disabled"> Sunday </label> </span> </td> 
							<td> </td> 
							<td> </td> 
							<td> </td> 
							<td class="text-right "> Closed </td> 
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="col-md-12 strategy-box custom-hours-strategy hide" style="border-bottom: 1px dashed #ccc; ">
			<span class="f-s-20"> Custom Hours</span> 
			<div class="table-responsive">
				<table class="table ">
					<tbody >
						<tr class="f-s-15 timepicker"> 
							<td class="p-l-35 weekdays"> 
								<span class=""> 
									<label class="checkbox"> 
										<input type="checkbox" checked="checked" name="mon_en" class="weekday"> Monday 
									</label> 
								</span> 
							</td> 
							<td class="timebox"> 
								<span>
									<input type="text" name="mon_start" class="form-control f-s-15 time start">
								</span> 
							</td> 
							<td class="timebox"> <span> to </span> </td> 
							<td class="timebox"> 
								<span>
									<input type="text" name="mon_end" class="form-control f-s-15 time end"> 
								</span>
							</td> 
							<td class="text-right weekday-status "> <span> Open </span> </td> 
						</tr>
						<tr class="f-s-15 timepicker"> 
							<td class="p-l-35"> 
								<span> 
									<label class="checkbox"> 
										<input type="checkbox" checked="checked" name="tue_en" class="weekday"> Tuesday 
									</label> 
								</span> 
							</td> 
							<td class="timebox"> 
								<span>
									<input type="text" name="tue_start" class="form-control f-s-15 time start"> 
								</span>
							</td> 
							<td class="timebox"> <span> to </span> </td> 
							<td class="timebox"> 
								<span>
									<input type="text" name="tue_end" class="form-control f-s-15 time end"> 
								</span>
							</td> 
							<td class="text-right weekday-status "> <span> Open </span> </td> 
						</tr>
						<tr class="f-s-15 timepicker"> 
							<td class="p-l-35"> 
								<span> 
									<label class="checkbox"> 
										<input type="checkbox" checked="checked" name="wed_en" class="weekday"> Wednesday 
									</label> 
								</span> 
							</td> 
							<td class="timebox"> 
								<span>
									<input type="text" name="wed_start" class="form-control f-s-15 time start"> 
								</span>
							</td> 
							<td class="timebox"> <span> to </span> </td> 
							<td class="timebox"> 
								<span>
									<input type="text" name="wed_end" class="form-control f-s-15 time end"> 
								</span>
							</td> 
							<td class="text-right weekday-status "> <span> Open </span> </td> 
						</tr>
						<tr class="f-s-15 timepicker"> 
							<td class="p-l-35"> 
								<span> 
									<label class="checkbox"> 
										<input type="checkbox" checked="checked" name="thu_en" class="weekday"> Thursday 
									</label> 
								</span> 
							</td> 
							<td class="timebox"> 
								<span>
									<input type="text" name="thu_start" class="form-control f-s-15 time start"> 
								</span>
							</td> 
							<td class="timebox"> <span> to </span> </td> 
							<td class="timebox"> 
								<span>
									<input type="text" name="thu_end" class="form-control f-s-15 time end"> 
								</span>
							</td> 
							<td class="text-right weekday-status "> <span> Open </span> </td> 
						</tr>
						<tr class="f-s-15 timepicker"> 
							<td class="p-l-35"> 
								<span> 
									<label class="checkbox"> 
										<input type="checkbox" checked="checked" name="fri_en" class="weekday"> Friday 
									</label> 
								</span> 
							</td> 
							<td class="timebox"> 
								<span>
									<input type="text" name="fri_start" class="form-control f-s-15 time start"> 
								</span>
							</td> 
							<td class="timebox"> <span> to </span> </td> 
							<td class="timebox"> 
								<span>
									<input type="text" name="fri_end" class="form-control f-s-15 time end"> 
								</span>
							</td> 
							<td class="text-right weekday-status "> <span> Open </span> </td> 
						</tr>
						<tr class="f-s-15 timepicker"> 
							<td class="p-l-35"> 
								<span> 
									<label class="checkbox"> 
										<input type="checkbox" checked="checked" name="sat_en" class="weekday"> Saturday 
									</label> 
								</span> 
							</td> 
							<td class="timebox"> 
								<span>
									<input type="text" name="sat_start" class="form-control f-s-15 time start"> 
								</span>
							</td> 
							<td class="timebox"> <span> to </span> </td> 
							<td class="timebox"> 
								<span>
									<input type="text" name="sat_end" class="form-control f-s-15 time end"> 
								</span>
							</td>  
							<td class="text-right weekday-status "> <span> Open </span> </td> 
						</tr>
						<tr class="f-s-15 timepicker"> 
							<td class="p-l-35"> 
								<span> 
									<label class="checkbox"> 
										<input type="checkbox" checked="checked" name="sun_en" class="weekday"> Sunday 
									</label> 
								</span> 
							</td> 
							<td class="timebox"> 
								<span>
									<input type="text" name="sun_start" class="form-control f-s-15 time start"> 
								</span>
							</td> 
							<td class="timebox"> <span> to </span> </td> 
							<td class="timebox"> 
								<span>
									<input type="text" name="sun_end" class="form-control f-s-15 time end"> 
								</span>
							</td> 
							<td class="text-right weekday-status "> <span> Open </span> </td> 
						</tr>
					</tbody>
				</table>
			</div>
		</div>

	</form>

</div>

<div class="col-md-12 clearfix">
	
	<div class="panel panel-default">
		<div class="panel-heading">
		    <ul id="myTab" class="nav nav-tabs pull-right">
		        <li class="active">
		        	<a href="#open_hour_tab" data-toggle="tab" aria-expanded="true">
		        		<i class="fa fa-arrow-down"></i> <span class="hidden-xs"> Open Hours  </span>
		        	</a>
		        </li>
		        
		        <li class="">
		        	<a href="#closed_hour_tab" data-toggle="tab" aria-expanded="false">
		        		<i class="fa fa-recording"></i> <span class="hidden-xs"> Closed Hours </span>
		        	</a>
		        </li>
		        
		        <li class="">
		        	<a href="#office_holiday_tab" data-toggle="tab" aria-expanded="false">
		        		<i class="fa fa-recording"></i> <span class="hidden-xs"> Holidays </span>
	        		</a>
	        	</li>

		    </ul>
		    <h4 class="panel-title"> <span class="f-s-15"> Incoming Call Handling. </span> </h4>
		</div>
		<div id="myTabContent" class="tab-content">
		    <div class="tab-pane fade active in clearfix" id="call_flow_tab">
		        
		        @forelse($call_routes as $route)

		        	<div>
						<span> {{ $route->title }} </span>
						<span> {!! $route->is_greeting_tts ? nl2br($route->greeting_tts) : "<audio src='". asset("storage/".$route->welcome->path) ."' controls></audio>" !!} </span>		   
						<span>

							@foreach($route->actions as $action)
			
								<p> $action->destination }}</p>
			
							@endforeach

						</span>


		        	</div>

		        @empty


		        @endforelse

		    </div>
		    <div class="tab-pane fade clearfix" id="closed_hour_tab">
		        
		        
		    </div>
		    <div class="tab-pane fade clearfix" id="office_holiday_tab">
		        

		    </div>

		</div>
	</div>

</div>