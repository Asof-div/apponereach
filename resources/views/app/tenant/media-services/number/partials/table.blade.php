<div class="table-responsive bg-white p-5">
	
	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr>
				<th>S/N</th>
				<th>Number</th>
				<th>Display Name</th>
				<th>Assigned User</th>
				<th>Short Code</th>
				<th>Find Me, Follow Me</th>
				<th>ACTION</th>
			</tr>
		</thead>
		<tbody>

            @foreach($numbers->where('slot', 0) as $index =>  $number)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    @if($number->empty)
                        <th></th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                        	<button class="btn btn-info btn-xs" data-toggle="modal" data-target=".add-number-modal"> <i class="fa fa-plus-circle"></i> Add Number </button> 
                        </td>
                    @else
                        <th>{{ $number->number }}</th>
                        <td>{{ $number->name }}</td>
	                	<td>{{ $number->user ? $number->user->name : '' }}</td>
	                	<td>{{ $number->scode }} </td>
	                	<td>{{ $number->follow_me }}</td>
	                    <td>
	                    	<button class="btn btn-sm" data-toggle="modal" data-target=".features-number-modal" data-number_id="{{ $number->id }}" data-number_number="{{ $number->number }}" data-number_name="{{ $number->name }}" data-number_user_id="{{ $number->user_id }}" data-number_scode="{{ $number->scode }}" data-number_scode_flow_id="{{ $number->scode_flow_id }}" data-number_follow_me="{{ $number->follow_me }}"> <i class="fa fa-cogs fa-2x"></i> </button>
	                    </td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td colspan="7"><hr style="background-color: #51BB8D; height: 3px;" /></td>
            </tr>
            <tr>
                <th colspan="3">Extra MSISDN SLOT ({{ $extra_number }})</th>
                <th> Get Extra Slot For # 500 Monthly</th>
                <td></td>
            </tr>
            @foreach($numbers->where('slot', 1) as $index =>  $number)
                <tr>
                    @if($number->empty)
                        <td>{{ $index + 1 }}</td>
                        <th></th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                        	<button class="btn btn-info btn-xs"  data-toggle="modal" data-target=".add-slot-number-modal"> <i class="fa fa-plus-circle"></i> Add Number </button> 
                        </td>
                    @else
                        <td>{{ $index + 1 }}</td>
                        <th>{{ $number->number }}</th>
                        <td>{{ $number->name }}</td>
	                	<td>{{ $number->user ? $number->user->name : '' }}</td>
	                	<td>{{ $number->scode }} </td>
	                	<td>{{ $number->follow_me }}</td>
	                    <td>
	                    	<button class="btn btn-sm" data-toggle="modal" data-target=".features-number-modal" data-number_id="{{ $number->id }}" data-number_number="{{ $number->number }}" data-number_name="{{ $number->name }}" data-number_user_id="{{ $number->user_id }}" data-number_scode="{{ $number->scode }}" data-number_scode_flow_id="{{ $number->scode_flow_id }}" data-number_follow_me="{{ $number->follow_me }}"> <i class="fa fa-cogs fa-2x"></i> </button>
	                    </td>
                    @endif
                </tr>
            @endforeach
		</tbody>
	</table>

</div>
