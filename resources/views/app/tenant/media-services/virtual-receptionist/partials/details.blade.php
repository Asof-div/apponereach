<div class="panel panel-with-tabs clearfix p-t-20 p-r-20 p-l-20 p-b-15" >
	<h4 class="text-primary">VIRTURAL RECEPTIONIST INFO <span class="pull-right"> <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target=".edit-virtual-receptionist-modal" data-backdrop="static" ><i class="fa fa-pencil-square-o"></i> Edit </button> </span></h4>
	<div class="table-responsive">
		<table class="table">
			<tbody>
				<tr>
					<th> Name </th>
					<td> <span class="f-s-15"> {{ $receptionist->name }} </span> </td>
					<th> IVR Message </th>
					<td><span class="f-s-15"> {!! $receptionist->ivr_type == "tts" ? nl2br($receptionist->ivr_msg) : "<audio src='". asset("storage/".$receptionist->ivr_media->path) ."' controls></audio>" !!} </span></td>
				</tr>
			</tbody>
		</table>
	</div>

</div>


<div class="panel panel-with-tabs clearfix p-t-20 p-r-20 p-l-20 p-b-15" >
	<h4 class="text-primary">VIRTUAL RECEPTIONIST MENU <span class="pull-right"> <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target=".delete-virtual-receptionist-modal"><i class="fa fa-trash"></i> Delete </button> </span></h4>
	<div class="table-responsive">
		<table class="table">
			<tbody>
				@foreach($receptionist->menus as $menu)
					<tr>
						<td> Press {{ $menu->key_press }} </td>
						<td> {{ $menu->action_label }} </td>
						<td> {{ $menu->icon .' '. $menu->value }} </td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

</div>

