
<div class="alert alert-success alert-dismissable print-flash-msg" @if(!Session::has('flash_message')) style="display:none" @endif>
	<button type="button" class="close close-alert"  aria-hidden="true">&times;</button>

	<ul>
		@if(Session::has('flash_message'))
			<li>{{Session::get('flash_message')}}</li>
		@endif
	</ul>

</div>