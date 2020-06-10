<div class="panel panel-default">
	<div class="panel-heading">
	    <ul id="myTab" class="nav nav-tabs pull-right">
	        <li class="active">
	        	<a href="#call_flow_tab" data-toggle="tab" aria-expanded="true">
	        		<i class="fa fa-arrow-down"></i> <span class="hidden-xs"> Default Call Flow  </span>
	        	</a>
	        </li>
	        <li class=""><a href="#auto_attendant_tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-recording"></i> <span class="hidden-xs"> Auto Attendant </span></a></li>

	    </ul>
	    <h4 class="panel-title"> <span class="f-s-20"> {{ $pilot_line->number }} </span> </h4>
	</div>
	<div id="myTabContent" class="tab-content">
	    <div class="tab-pane fade active in clearfix" id="call_flow_tab">
	        
	        @include('app.tenant.media-services.pilot_line.partials.default')
	        

	    </div>
	    <div class="tab-pane fade clearfix" id="auto_attendant_tab">
	        
	        @include('app.tenant.media-services.pilot_line.partials.auto_attendant')

	    </div>

	</div>
</div>