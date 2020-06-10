@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create Room</div>

                <div class="panel-body">
                    
                    <form method="POST" action="{{ route('room.store') }}">
                    	{{ csrf_field() }}

                    	<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    		<label>Room Name</label>
                    		<input type="text" name="name" class="form-control">

                    		@if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                    	</div>

                    	<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                    		<label>Room Type</label>
                    		<select name="type" class="form-control">
                    			<option value="">Select Room Type</option>
                    			<option value="private">Private</option>
                    			<option value="group">Group</option>
                    		</select>

                    		@if ($errors->has('type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('type') }}</strong>
                                </span>
                            @endif
                    	</div>

                    	<button class="btn btn-default" type="submit">Create</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
