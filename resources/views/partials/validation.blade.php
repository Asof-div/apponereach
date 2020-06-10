@if(count($errors))
	
	<div class="alert alert-default alert-dismissable">
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    @foreach($errors->all() as $error)

	    	<li class="text text-danger m-t-5">
	    		<span class="f-s-14"> {{$error}} </span>
	    	</li>

	    @endforeach
	</div>


@endif

<div class="alert alert-default alert-dismissable print-error-msg" style="display:none">
	<button type="button" class="close close-alert"  aria-hidden="true">&times;</button>

	<ul class="text-danger f-s-14"></ul>

</div>