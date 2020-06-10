<div class="form-group">
	<select class="form-control history-list">
		@foreach($invoice->historys as $history)
			<option value="{{ asset('storage/'.$history->path) }}">{{ $history->pivot->update }}</option>
		@endforeach
	</select>
</div>


<div>
	
	<object id="preview_content" data="{{ $invoice->historys->first() ? asset('storage/'.$invoice->historys->first()->path) : '' }}" height="900" data="" style="width: 100%;"></object>

</div>