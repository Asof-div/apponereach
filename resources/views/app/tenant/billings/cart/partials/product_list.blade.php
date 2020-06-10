
<div class="col-md-12 p-0">

	<ul class="nav nav-tabs nav-theme">
		<li class="active"><a href="#nav-package" data-toggle="tab"> Package </a></li>
		<li><a href="#nav-pilot" data-toggle="tab"> Pilot Number </a></li>
		<li><a href="#nav-cug" data-toggle="tab"> Number Slot </a></li>
	</ul>
	<div class="tab-content bg-silver">
		<div class="tab-pane fade clearfix active in" id="nav-package">

			<div class="cd-pricing-container cd-has-margins m-0">

                <ul class="cd-pricing-list cd-bounce-invert overflow-y height-600">
                    @foreach($packages as $package)
                        <li class="{{ $package->id == $tenant->package_id ? 'cd-popular' : '' }}">
                            <ul class="cd-pricing-wrapper">
                                <li data-type="monthly" class="is-ended is-visible">
                                    <header class="cd-pricing-header">
                                        <h2>{{ $package->name }}</h2>
                                        <div class="cd-price">
                                            <span class="cd-currency">&#x20A6;</span>
                                            <span class="cd-value">{{ number_format($package->price) }}</span>
                                            <span class="cd-duration">mo</span>
                                        </div>
                                    </header> 
                                    <div class="cd-pricing-body ">
                                        <ul class="cd-pricing-features">
                                            <li><em>{{ $package->msisdn_limit }}</em> CUG Number Limit</li>
                                            <li class="">{{ $package->note }}</li>

                                        </ul>
                                    </div>
                                    <footer class="cd-pricing-footer">
                                        <a class="cd-select" href="#">Select</a>
                                    </footer>
                                </li>

                            </ul>
                        </li>
                    @endforeach
    
                </ul>
            </div>
 
            <div>
            	<button type="button" class="btn btn-success">Add To Cart </button>
            </div>
		</div>
		<div class="tab-pane fade" id="nav-pilot">
		    
			{{-- @include('app.tenant.billings.number_manager.pilot_line.partials.table')				 --}}

		</div>
		<div class="tab-pane clearfix fade" id="nav-cug">
		    
		    <div class="col-md-3 thumbnail text-center">
		    	<label class="f-s-16 full-display text-center">
    				<h4> 1 Slot </h4>
			    	<input type="radio" name="number_slot" value="1">
                    <div>
                    	<span class="">&#x20A6; 500 </span>
                    </div>
                </label>
		    </div>

		    <div class="col-md-3 thumbnail text-center">
		    	<label class="f-s-16 full-display text-center">
    				<h4> 3 Slot </h4>
			    	<input type="radio" name="number_slot" value="3">
                    <div>
                    	<span class="">&#x20A6; 1500 </span>
                    </div>
                </label>
		    </div>

		    <div class="col-md-3 thumbnail text-center">
		    	<label class="f-s-16 full-display text-center">
    				<h4> 5 Slot </h4>
			    	<input type="radio" name="number_slot" value="5">
                    <div>
                    	<span class="">&#x20A6; 2500 </span>
                    </div>
                </label>
		    </div>

		    <div class="col-md-3 thumbnail text-center">
		    	<label class="f-s-16 full-display text-center">
    				<h4> 7 Slot </h4>
			    	<input type="radio" name="number_slot" value="7">
                    <div>
                    	<span class="">&#x20A6; 3500 </span>
                    </div>
                </label>
		    </div>

		    <div class="col-md-3 thumbnail text-center">
		    	<label class="f-s-16 full-display text-center">
    				<h4> 10 Slot </h4>
			    	<input type="radio" name="number_slot" value="10">
                    <div>
                    	<span class="">&#x20A6; 5000 </span>
                    </div>
                </label>
		    </div>

		    <div class="col-md-3 thumbnail text-center">
		    	<label class="f-s-16 full-display text-center">
    				<h4> 15 Slot </h4>
			    	<input type="radio" name="number_slot" value="15">
                    <div>
                    	<span class="">&#x20A6; 7500 </span>
                    </div>
                </label>
		    </div>

		    <div class="col-md-3 thumbnail text-center">
		    	<label class="f-s-16 full-display text-center">
    				<h4> 20 Slot </h4>
			    	<input type="radio" name="number_slot" value="20">
                    <div>
                    	<span class="">&#x20A6; 10000 </span>
                    </div>
                </label>
		    </div>
		 
		    <div class="col-md-3 thumbnail text-center">
		    	<label class="f-s-16 full-display text-center">
    				<h4> 25 Slot </h4>
			    	<input type="radio" name="number_slot" value="25">
                    <div>
                    	<span class="">&#x20A6; 12500 </span>
                    </div>
                </label>
		    </div>

		    <div class="col-md-3 thumbnail text-center">
		    	<label class="f-s-16 full-display text-center">
    				<h4> 30 Slot </h4>
			    	<input type="radio" name="number_slot" value="30">
                    <div>
                    	<span class="">&#x20A6; 15000 </span>
                    </div>
                </label>
		    </div>

		    <div class="col-md-3 thumbnail text-center">
		    	<label class="f-s-16 full-display text-center">
    				<h4> 35 Slot </h4>
			    	<input type="radio" name="number_slot" value="35">
                    <div>
                    	<span class="">&#x20A6; 17500 </span>
                    </div>
                </label>
		    </div>

		    <div class="col-md-3 thumbnail text-center">
		    	<label class="f-s-16 full-display text-center">
    				<h4> 40 Slot </h4>
			    	<input type="radio" name="number_slot" value="40">
                    <div>
                    	<span class="">&#x20A6; 20000 </span>
                    </div>
                </label>
		    </div>

		    <div class="col-md-12 ">
		    	<button type="button" class="btn btn-success"> Add To Cart </button>
		    </div>

		</div>
		
	</div>
</div>