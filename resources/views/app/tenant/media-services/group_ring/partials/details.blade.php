<div class="panel panel-default">
	<div class="panel-heading">
	    <ul id="myTab" class="nav nav-tabs pull-right">
	        <li class="active"><a href="#group_info_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-home"></i> <span class="hidden-xs"> Info </span></a></li>
	        {{-- <li class=""><a href="#call_flow_tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-tty"></i> <span class="hidden-xs"> Call Flow </span></a></li> --}}
	        <li class=""><a href="#call_history_tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-history"></i> <span class="hidden-xs"> Call History </span></a></li>
	    </ul>
	    <h4 class="panel-title"> <span class="f-s-15"> Group Configuration </span> </h4>
	</div>
	<div id="myTabContent" class="tab-content">
	    <div class="tab-pane fade active in" id="group_info_tab">
	        <div>
				<div class="f-s-14">Group Infomation</div>
				
				<br/>

				<div class="table-responsive">
					
					<table class="table">
						<tr>
							<th> Number </th>
							<td> {{ $group->number }} </td>
						</tr>
						<tr>
							<th> Group Name </th>
							<td> {{ $group->name }} </td>
						</tr>
						<tr>
							<th> Method </th>
							<td> {{ $group->method }} </td>
						</tr>
						<tr>
							<th colspan="2"> Members </th>
						</tr>
						@foreach($group->members as $member)

							<tr>
								@php $type = trim(strtolower($member['type'])) == 'number' ? 'fa fa-phone' : 'fa fa-tty'; @endphp
								<td> {!! "<i class='". $type ."'> </i> &nbsp;  ". $member['number'] !!}</td>
								<td> </td>
							</tr>

						@endforeach

						<tr>
							<td><button class="btn" data-toggle="modal" data-target=".edit-group-ring-configuration-modal" data-backdrop="static" ><i class="fa fa-pencil-square-o"></i> Edit</button></td>
							<td><button class="btn btn-warning" data-toggle="modal" data-target=".delete-group-ring-modal"><i class="fa fa-trash"></i> Delete</button></td>
						</tr>


					</table>

				</div>	        	
	        </div>
	    </div>
	    {{-- <div class="tab-pane fade" id="call_flow_tab">
	        <div class="f-s-14"> Group Call Flow  </div>
	        <p>This this interpete the action at occurs when this number is called. </p>
	        
	        <br/>

	        <div class="clearfix">
	        	<div class="col-md-6 col-sm-8 col-xs-12 col-md-offset-3 col-sm-2">
	        		<ul class="list-unstyled ">
	        			<li class="m-b-15">
	        				<div class="btn-group btn-group-justified">
	        					<div class="btn-group">
									<a href="javascript:;" data-toggle="dropdown" class="btn btn-default dropdown-toggle" aria-expanded="true"> Add Action <span class="caret"></span></a><div class="dropdown-backdrop"></div>
									<ul class="dropdown-menu">
										<li><a href="javascript:;"> Extension </a></li>
										<li><a href="javascript:;"> Group Ring </a></li>
										<li><a href="javascript:;"> Hunt Group </a></li>
										<li><a href="javascript:;"> Playback </a></li>
										<li><a href="javascript:;"> Virtual Receptionist </a></li>
										<li class="divider"></li>
										<li><a href="javascript:;"> Voicemail </a></li>
									</ul>
								</div>
	        					<a class="btn btn-success"> Save Changes </a>
	        				</div>
	        			</li>
	        			<li class="m-5 clearfix"> <span class="f-s-18"> Incoming call ... </span><span class="f-s-18 pull-right">{{ $group->call_flow->exten . " - " . $group->call_flow->name}} </span> </li>

	        		</ul>
        			<input type="hidden" name="call_flow_id" value="{{ $group->call_flow_id }}">
	        		<ul class="list-unstyled call-flow-order">
	        			@foreach($group->call_flow->actions as $flow_action)

	        				<li class="">
	        					<input type="hidden" name="flow_action_id[]" value="{{ $flow_action->id }}">
	        					<input type="hidden" name="flow_action_action[]" value"{{ $flow_action->action }}">
	        					<input type="hidden" name="flow_action_module_type[]" value="{{ $flow_action->module_type }}">
	        					<input type="hidden" name="flow_action_module_id[]" value="{{ $flow_action->module_id }}">
	        					<div class="text-center"> <i class="fa fa-long-arrow-down fa-2x"></i> </div>
	        					<div class="call-flow-component m-5 p-10">
		        					@if($flow_action->removeable)<span class="pull-right"> <button type="button" class="btn-link f-s-18 text-danger"> &times; </button></span> @endif
		        					<div class="text-center"> <i class="{{ $flow_action->flow_icon }} fa-2x"></i> &nbsp; <span class="f-s-13"> {{ $flow_action->type }} </span> </div>
		        					<div class="text-center m-t-10"> {{ $flow_action->module->numbers }} </div>
	        					</div>
	        				</li>

	        			@endforeach
	        		</ul>
	        	</div>
	        </div>
	    </div> --}}
	    <div class="tab-pane fade" id="call_history_tab">
	    	<div class="f-s-14"> Group Call History </div>

	    </div>
	</div>
</div>