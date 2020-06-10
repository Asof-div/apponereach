<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-title"> <span class="h3"> &nbsp; {{ $inbox->title .' - ' . $inbox->user }} </span></div>
	</div>
	<div class="panel-body">
		
		<div class="col-md-12">

			<ul class="nav nav-pills">
				<li class="active"><a href="#nav-dashboard" data-toggle="tab"> DETAILS </a></li>
				<li><a href="#nav-plan" data-toggle="tab"> VOICE MESSAGES </a></li>

			</ul>
			<div class="tab-content">
				<div class="tab-pane fade active in" id="nav-dashboard">
				    <h3 class="m-t-10 clearfix"> Details 
				    	<span class="pull-right">
				    		<button type="button" class="btn btn-xs btn-primary" data-target=".edit-inbox-modal" data-backdrop="static" data-toggle="modal"> <i class="fa fa-edit"></i> EDIT </button>
				    		<button type="button" class="btn btn-xs btn-danger" data-target=".delete-inbox-modal" data-toggle="modal"> <i class="fa fa-trash"></i> DELETE </button>
				    	</span> 
				    </h3>
					<div class="table-responsive">
						<table class="table table-striped">
							<tbody class="f-s-18">
								<tr >
									<th> TITLE </th> 
									<td class="text-primary"> {{ $inbox->title }} </td>
								</tr>
								<tr>
									<th> USER </th>
									<td> {{ $inbox->user }} </td>
								</tr>
								<tr>
									<th> EMAIL </th>
									<td>{{ $inbox->email }}</td>
								</tr>
								<tr>
									<th> VOICEMAIL PROMPT </th>
									<td> @if($inbox->prompt) <audio controls="controls" src="{{ asset('storage/'.$inbox->sound_path) }}" ></audio> @endif </td>
								</tr>
								<tr>
									<th>LEAVE A COPY ON PORTAL</th>
									<td>{{ $inbox->leave_copy_on_portal ? "YES" : "NO" }}</td>
								</tr>
								<tr>
									<th>SEND VOICEMAIL TO MAIL</th>
									<td>{{ $inbox->send_voicemail_to_mail ? "YES" : "NO" }}</td>
								</tr>

							</tbody>
						</table>
					</div>
				</div>
				<div class="tab-pane fade" id="nav-plan">
				    <h3 class="m-t-10">Voice Messages </h3>
					<div class="table-responsive">
						<table class="table table-striped">
							<tbody class="f-s-18">
								

							</tbody>
						</table>
					</div>
				</div>


			</div>
		</div>

	</div>
</div>