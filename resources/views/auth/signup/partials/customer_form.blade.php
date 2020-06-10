<form action="{{ route('register') }}" method="POST" class="margin-bottom-0 clearfix">
    {{ csrf_field() }}
    
    @include('partials.validation')

    <div class="col-md-12 p-0 clearfix">

        <div class="panel panel-default m-t-20">
            <div class="panel-body">
                
                <div class="col-md-12">
                    <input type="hidden" value="{{ $plan->getPlan()}}" name="plan">
                    <div class="form-group clearfix">
                        <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Package Info: </label>
                        <div class="col-md-8">
                            <div class="f-s-18 f-w-600"> {{ $plan->getPackage()->name }} </div>
                            <div class="f-s-18 f-w-600"> {{ $plan->getCurrency().number_format($plan->getPrice(), 2) }} </div>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Duration: </label>
                        <div class="col-md-8">
                            <div class="f-s-18 f-w-600"> {{ $plan->getDuration() }} </div>

                        </div>
                    </div>
                        
                    <div class="form-group clearfix">
                        <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Corporation Name: </label>
                        <div class="col-md-8">
                            <input type="text" name="corporation_name" class="form-control" value="{{ old('corporation_name') }}">
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Corporation Short Name </label>
                        <div class="col-md-8">
                            <input type="text" name="corporation_short_name" class="form-control domain-input" value="{{ old('corporation_short_name') }}">
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="form-label f-s-16 col-md-12"> <i class="fa fa-asterisk text-danger f-s-12"></i> Corporation Link: </label>
                        <div class="col-md-12">
                            <p class="f-s-16 text-success f-w-500 form-control"> <i class="fa fa-arrow-right"></i> <span class="domain-url-text f-s-16">{{ request()->root() }}/tenant/{{ old('corporation_short_name') }} </span></p>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Email: </label>
                        <div class="col-md-8">
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> First Name: </label>
                        <div class="col-md-8">
                            <input type="text" name="firstname" class="form-control" value="{{ old('firstname') }}">
                        </div>
                    </div>


                    <div class="form-group clearfix">
                        <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Last Name: </label>
                        <div class="col-md-8">
                            <input type="text" name="lastname" class="form-control" value="{{ old('lastname') }}">
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Mobile No.: </label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="mobile_no" value="{{ old('mobile_no') }}" />
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Password: </label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" name="password" value="" />
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Password Confirm: </label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" name="password_confirmation" value="" />
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="col-md-12 m-r-0 m-l-0 p-15 clearfix" style="background-color: #b4c404;">
        <div class="pull-left" >
            <a href="{{ route('register') }}" class="btn btn-default"> <i class="fa fa-back"></i> Back </a>
        </div>
        <div class="pull-right" >
            <button class="btn btn-success" type="submit"> <i class="fa fa-save"></i> Next </button>
        </div>
    </div>     
    
</form>