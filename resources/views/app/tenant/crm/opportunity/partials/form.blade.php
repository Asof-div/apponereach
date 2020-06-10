<form id="account_form" class="form-horizontal" name="13" action="" enctype='multipart/form-data' method='post'>

    
    <div class="form-group bg-white clearfix account-form-box p-t-25 m-r-0 m-l-0 m-t-15 hidden" >
        
        <div class="col-md-6 col-sm-6">
            
            <div class="form-group">
                <label class="form-label col-md-4">Account Name <i class="fa fa-asterisk"></i></label>
                <div class="col-md-8">
                    <input type="text" name="name" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Address Line</label>
                <div class="col-md-8">
                    <textarea class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">City</label>
                <div class="col-md-8">
                    <input type="text" name="city" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">State</label>
                <div class="col-md-8">
                    <input type="text" name="state" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Postcode/Zip</label>
                <div class="col-md-8">
                    <input type="text" name="postcode" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Country <i class="fa fa-asterisk"></i></label>
                <div class="col-md-8">
                    <select name="country" class="form-control">
                        @foreach($countries as $country)
                            <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Email</label>
                <div class="col-md-8">
                    <input type="text" name="email" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Phone</label>
                <div class="col-md-8">
                    <input type="text" name="phone" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Website</label>
                <div class="col-md-8">
                    <input type="text" name="website" class="form-control">
                </div>
            </div>

        </div>

        <div class="col-md-6 col-sm-6">
            
            <div class="form-group">
                <label class="form-label col-md-4">Type</label>
                <div class="col-md-8">
                    <select name="type" class="form-control">
                        @foreach($types as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Owner <i class="fa fa-asterisk"></i></label>
                <div class="col-md-8">
                    <select name="owner" class="form-control">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"> {{ $user->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Source</label>
                <div class="col-md-8">
                    <select name="source" class="form-control">
                        @foreach($sources as $source)
                            <option value="{{ $source }}">{{ $source }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Sector <i class="fa fa-asterisk"></i></label>
                <div class="col-md-8">
                    <select name="industry" class="form-control">
                        @foreach($industries as $industry)
                            <option value="{{ $industry->id }}">{{ $industry->label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Currency</label>
                <div class="col-md-8">
                    <select name="currency" class="form-control">
                        @foreach($currencies as $currency)
                            <option value="">{{ $currency->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-md-4">Notes <i class="fa fa-asterisk"></i></label>
                <div class="col-md-8">
                    <textarea class="form-control" rows="5" name="notes"></textarea>
                </div>
            </div>


        </div>

        <div class="col-md-12 bg-white clearfix p-15 ">

            <div class="pull-right" >
                <button class="btn btn-default close-account" type="button"> <i class="fa fa-exclamation-circle"></i> Cancel</button> &nbsp;
                <button class="btn btn-primary" type="submit"> <i class="fa fa-save"></i> Save Account </button>
            </div>
        
        </div>

        <div class="col-md-12 bg-white clearfix p-15 ">
        
            <div class="text-center">
                <span class="fa-stack fa-2x">
                    <i class="fa fa-square-o fa-stack-2x"></i>
                    <i class="fa fa-long-arrow-down fa-stack-1x"></i>
                </span>
            </div>

        </div>
    </div>


</form>



<form id="opportunity_form" class="form-horizontal" name="13" action="" enctype='multipart/form-data' method='post'>

	<div class="form-group bg-white clearfix p-15 m-r-0 m-l-0 m-t-15 save-opportunity">
        <div class="pull-right" >
            <button class="btn btn-default" type="reset"> <i class="fa fa-exclamation-circle"></i> Cancel</button> &nbsp;
            <button class="btn btn-success" type="submit"> <i class="fa fa-save"></i> Save Opportunity </button>
        </div>
    </div>   

    <div class="form-group bg-white clearfix p-15 m-r-0 m-l-0 m-t-15 ">
        <div class="col-md-6 col-sm-6 ">
            <label class="form-label col-md-4">Account <i class="fa fa-asterisk"></i> </label>
            <div class="col-md-8">
                <select name="account" class="form-control account-select">
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 col-sm-6">
            <button class="btn btn-primary toggle-account" type="button"> <i class="fa fa-plus"></i> ADD NEW ACCOUNT </button>
        </div>

    </div>     
    
    <div class="p-20 col-md-12 bg-white m-b-15 clearfix">

        {{csrf_field()}}
        
        <div class="col-md-6 col-sm-6">
            <div class="form-group">
                <label class="form-label col-md-4"> Opportunity Title <i class="fa fa-asterisk"></i> </label>
                <div class="col-md-8">
                    <input type="text" name="title" class="form-control ">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Close Date </label>
                <div class="col-md-8">
                    <input type="text" name="close_date" class="form-control datepicker">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Stage  <i class="fa fa-asterisk"></i></label>
                <div class="col-md-8">
                    <select name="stage" class="form-control status">

                        <option value="New Lead">New Lead</option>                    	
                        <option value="Lost"> Lost </option>
                        <option value="Won"> Won </option>
                        <option value="Qualified"> Qualified </option>
                        <option value="Waiting"> Waiting </option>
                        <option value="Meeting"> Meeting </option>
                        <option value="Shared Quotation"> Shared Quotation </option>
                        <option value="Waiting For PO"> Waiting For PO</option>
                        <option value="Invoiced"> Invoiced </option>
                    </select>
                </div>
            </div>
            
            
            <div class="form-group">
                <label class="form-label col-md-4"> Attention <i class="fa fa-asterisk"></i></label>
                <div class="col-md-8">
                    <select name="attention" class="form-control">
                    	
                            <option value="Cold"> Cold </option>
                            <option value="Warm"> Warm </option>
                    		<option value="Hot"> Hot </option>

                    	
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Probability</label>
                <div class="col-md-8">
                    <select name="probability" class="form-control">
                        <option value="5">5 % </option>
                        <option value="15">15 % </option>
                        <option value="25">25 % </option>
                        <option value="35">35 % </option>
                        <option value="50">50 % </option>
                        <option value="75">75 % </option>
                        <option value="90">90 % </option>
                    	<option value="100">100 % </option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Competitor </label>
                <div class="col-md-8">
                    <select name="competitor" class="form-control ">
                        <option value=""> &dash; &dash; &dash;  Select Competitor  &dash; &dash; &dash; </option>
                    	@foreach($accounts as $account)

                    		<option value="{{ $account->id }}"> {{ $account->name }} </option>

                    	@endforeach                       
                    </select>
                </div>
            </div>

            <div class="form-group  ">
            	<label class="form-label col-md-4"> Repeat Order </label>
            	<div class="col-md-8">
                    <label class="switch">
                    <input type="checkbox" name="repeat_order">
                    <span class="slider "></span>
                </label>
            	</div>
            </div>

            
        </div>

        <div class="col-md-6 col-sm-6">

            <div class="form-group">
                <label class="form-label col-md-4">Source <i class="fa fa-asterisk"></i></label>
                <div class="col-md-8">
                    <select name="source" class="form-control">
                        @foreach($sources as $source)
                            <option value="{{ $source }}">{{ $source }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Currency</label>
                <div class="col-md-8">
                    <select name="currency" class="form-control">
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-md-4">Description <i class="fa fa-asterisk"></i> </label>
                <div class="col-md-8">
                    <textarea class="form-control" rows="7" name="description"></textarea>
                </div>
            </div>
            
        </div>


    </div>

	
</form>