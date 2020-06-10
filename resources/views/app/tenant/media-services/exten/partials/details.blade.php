<div class="panel panel-default">
	<div class="panel-heading">
	    <ul id="myTab" class="nav nav-tabs pull-right">
	        <li class="active"><a href="#extension_info_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-home"></i> <span class="hidden-xs"> Info </span></a></li>
	        <li class=""><a href="#device_provisioning_tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-tty"></i> <span class="hidden-xs"> Device Provisioning </span></a></li>
	    </ul>
	    <h4 class="panel-title"> <span class="f-s-15"> Extension Configuration </span> </h4>
	</div>
	<div id="myTabContent" class="tab-content">
	    <div class="tab-pane fade active in" id="extension_info_tab">
	        <div>
				<div class="f-s-14">Extension Infomation</div>
				
				<br/>

				<div class="table-responsive">
					
					<table class="table">
						<tr>
							<th> Number </th>
							<td> {{ $exten->number }} </td>
						</tr>
						<tr>
							<th> Caller ID Name </th>
							<td> {{ $exten->name }} </td>
						</tr>
						<tr>
							<th> User Account </th>
							<td> {{ $exten->user->name }} </td>
						</tr>
						<tr>
							<th> Voicemail </th>
							<td> {!! $exten->voicemail ? "<span class='text-success'> YES </span>" : "<span class='text-danger'> NO </span>" !!} </td>
						</tr>
						<tr>
							<th> Call Recording </th>
							<td> {!! $exten->call_recording ? "<span class='text-success'> YES </span>" : "<span class='text-danger'> NO </span>" !!} </td>
						</tr>
						<tr>
							<th colspan="2"> <span class="f-s-15 text-danger"> Phone Restrictions </span> </th>
						</tr>
						<tr>
							<th> 9 Mobile Numbers </th>
							<td> {!! $exten->permit_same_did ? "<span class='text-success'> Allowed </span>" : "<span class='text-danger'> Not Allowed </span>" !!} </td>
						</tr>
						<tr>
							<th> Other Network eg. Airtel, MTN, GLO </th>
							<td> {!! $exten->permit_other_did ? "<span class='text-success'> Allowed </span>" : "<span class='text-danger'> Not Allowed </span>" !!} </td>
						</tr>
						<tr>
							<th> International Numbers </th>
							<td> {!! $exten->permit_international_did ? "<span class='text-success'> Allowed </span>" : "<span class='text-danger'> Not Allowed </span>"  !!} </td>
						</tr>
						<tr>
							<th> Primary Outbound Route </th>
							<td> {{ $exten->primary_outbound_did }} </td>
						</tr>
						<tr>
							<th> Secondary Outbound Route </th>
							<td> {{ $exten->secondary_outbound_did }} </td>
						</tr>
						
						<tr>
							<th colspan="2"> <span class="f-s-15 text-danger"> SIP Registration Credentials </span> </th>
						</tr>
						<tr>
							<th> Server </th>
							<td> <span class="f-s-15">197.156.250.182 or sip1.telvida.com </span> </td>
						</tr>
						<tr>
							<th> Username  </th>
							<td> <span class='f-s-15'>{{ $exten->exten_reg }}</span> </td>
						</tr>
						<tr>
							<th> Password </th>
							<td> <span class='f-s-15'> {{ $exten->password }} </span> </td>
						</tr>
						

						<tr>
							<td><button class="btn" data-toggle="modal" data-target=".edit-extension-configuration-modal" data-backdrop="static" ><i class="fa fa-pencil-square-o"></i> Edit</button></td>
							<td><button class="btn btn-warning" data-toggle="modal" data-target=".delete-extension-modal"><i class="fa fa-trash"></i> Delete</button></td>
						</tr>


					</table>

				</div>	        	
	        </div>
	    </div>
	    <div class="tab-pane fade" id="device_provisioning_tab">
		    <div class="f-s-14 clearfix"> Extension Device Provisioning  
			    <span class="pull-right">
				    <div class="btn-group m-r-5 m-b-5">
						<a href="javascript:;" data-toggle="dropdown" class="btn btn-default dropdown-toggle" aria-expanded="false"> <i class=""></i> New Device <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="javascript:;" data-toggle="modal" data-target=".sip-device-modal" > SIP Phone </a></li>
							<li><a href="javascript:;" data-toggle="modal" data-target=".cell-device-modal"> Cell Phone </a></li>
							<li><a href="javascript:;" data-toggle="modal" data-target=".smart-device-modal"> Smartphone </a></li>
						</ul>
					</div>	
			    </span>
		    </div>

		    <div class="table-responsive m-t-20">
		    	<table class="table">
		    		<thead>
		    			<tr>
		    				<th>TYPE</th>
		    				<th>NAME / MAC ADDRESS </th>
		    				<th>USER</th>
		    				<th>ENABLE</th>
		    			</tr>
		    		</thead>
		    	</table>
		    </div>

	    </div>
	</div>
</div>