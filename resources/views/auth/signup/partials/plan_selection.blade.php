<form >
	
    @include('partials.validation')
    
	<div class="col-md-12 p-0 clearfix ">

        <div class="panel panel-default m-t-20">
            <div class="panel-body">
                
                <div class="col-md-12">
                        
                    @foreach($packages as $package)
                        <div class="col-md-4">
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
                                    
                                </li>
                                <li class="clearfix overflow-text-content">
                                    {{ implode(", ", $package->addons->pluck('label')->toArray() ) }}
                                </li>
                                <li>
                                    <label class="text-center width-full bg-info p-10 f-s-14">
                                        <input type="radio" name="plan" {{ strtolower(request()->plan) == strtolower('monthly_'. $package->name) ? 'checked' : '' }} value="monthly_{{ $package->name }}">
                                        MONTHLY
                                    </label>
                                </li>
                                <li class="m-t-10">
                                    <label class="text-center width-full bg-info p-10 f-s-14">
                                        <input type="radio" name="plan" {{ strtolower(request()->plan) == strtolower('annually_'. $package->name) ? 'checked' : '' }} value="annually_{{ $package->name }}">
                                        ANNUALLY   <span class="label label-danger pull-right">{{ number_format($package->discountOff(), 2).'% off' }} </span>
                                    </label>
                                    
                                </li>
                            </ul>
                        </div>
                    @endforeach

                </div>

            </div>
        </div>
    </div>

    <div class="col-md-12 m-r-0 m-l-0 p-15 clearfix" style="background-color: #b4c404;">
        <div class="pull-right" >
            <button class="btn btn-success" type="submit"> <i class="fa fa-save"></i> Next </button>
        </div>
    </div>     
    
</form>

