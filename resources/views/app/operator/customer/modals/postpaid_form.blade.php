<div class="modal fade edit-postpaid-modal" tabindex="-1" role="dialog" aria-labelledby="transfer_routeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                <h5 class="modal-title"> <span class="h3 text-info"> Create Post Paid Account </span> </h5> 
			</div>
			<div class="modal-body clearfix">
                <hr style="background-color: #51BB8D; height: 3px;" />
				<form method="post" id="create_postpaid" action="{{ route('operator.customer.update') }}">

					@include('partials.validation')
					@include('partials.flash_message')


                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">

					<div class="form-group clearfix">
						<label class="col-md-6 col-xs-12">Account Manager</label>
						<div class="col-md-6 col-xs-12">
							<select class="form-control">
								@foreach($operators as $operator)
									<option value="{{ $operator->id }}" {{ $operator->id == Auth::id() ? 'selected' : '' }}> {{ $operator->name }} </option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="form-group clearfix">
						<label class="col-md-6 col-xs-12">Package </label>
						<div class="col-md-6 col-xs-12">
							<select class="form-control">
								@foreach($packages as $package)
									<option value="{{ $package->id }}" > {{ $package->name }} </option>
								@endforeach
							</select>
						</div>
					</div>


					<div class="form-group clearfix">
		                <hr style="background-color: #51BB8D; height: 3px;" />
						<button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> Submit </button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<div class="modal fade add-number-modal" tabindex="1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" id="number_form" action="{{ route('operator.customer.number.store') }}">

            <div class="modal-content">
                <div class="modal-header"> 
            		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> <h5 class="modal-title"> <span class="h4 text-info"> ADD NUMBER TO CLOSED USER GROUP </span> </h5> 
                </div>
                <div class="modal-body clearfix">
	                <hr style="background-color: #51BB8D; height: 3px;" />

                    <div class="col-md-12 clearfix">
                        {{ csrf_field() }}
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        @include('partials.flash_message')
                        @include('partials.validation')
                        <span class="text-danger"> NOTE !!! &nbsp; </span> 
                        <span >Package Limit {{ $msisdn_limit }} : To exceed this limit you will require additional charges </span>
                    </div>
                    <div class="col-md-12 clearfix">  
                        <div class="form-group ">
                            <label class="f-s-14">Display Name </label>
                            <input class="form-control" type="text" name="name" placeholder="John" required="required">
                        </div>
                        <div class="form-group ">
                            <label class="f-s-14">Phone Number </label>
                            <input class="form-control" name="number" placeholder="08032902922" type="text">
                        </div>

                        <div>
                            <label class="f-s-14">Assigned User </label>
                            <select class="form-control" name="user_id">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>             

                    </div>

                </div>

                <div class="modal-footer">
	                <hr style="background-color: #51BB8D; height: 3px;" />
                    <div class="form-inline">
                        <div class="form-group m-r-10">
                            <button type="submit" class="btn btn-success add-number-btn"> <i class="fa fa-save"></i> Add Number </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<div class="modal fade add-slot-number-modal" tabindex="1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" id="number_extra_form" action="{{ route('operator.customer.number.store') }}">

            <div class="modal-content">
                <div class="modal-header"> 
            		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> <h5 class="modal-title"> <span class="h4 text-info"> ADD NUMBER TO CLOSED USER GROUP </span> </h5> 
                </div>
                <div class="modal-body clearfix">
	                <hr style="background-color: #51BB8D; height: 3px;" />

                    <div class="col-md-12 clearfix">
                        {{ csrf_field() }}
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <input type="hidden" name="slot" value="1">
                        @include('partials.flash_message')
                        @include('partials.validation')
                        <span class="text-danger"> NOTE !!! &nbsp; </span> 
                        <span >Package Limit {{ $msisdn_limit }} : To exceed this limit you will require additional charges </span>
                    </div>
                    <div class="col-md-12 clearfix">  
                        <div class="form-group ">
                            <label class="f-s-14">Display Name </label>
                            <input class="form-control" type="text" name="name" placeholder="John" required="required">
                        </div>
                        <div class="form-group ">
                            <label class="f-s-14">Phone Number </label>
                            <input class="form-control" name="number" placeholder="08032902922" type="text">
                        </div>

                        <div>
                            <label class="f-s-14">Assigned User </label>
                            <select class="form-control" name="user_id">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>             

                    </div>

                </div>

                <div class="modal-footer">
	                <hr style="background-color: #51BB8D; height: 3px;" />
                    <div class="form-inline">
                        <div class="form-group m-r-10">
                            <button type="submit" class="btn btn-success add-number-btn"> <i class="fa fa-save"></i> Add Number </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>