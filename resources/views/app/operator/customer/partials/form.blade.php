
<form id="customer_form" name="13" class="form-horizontal" method="POST" action="{{ route('operator.customer.store') }}">
    {{ csrf_field() }}


    <div class="form-group clearfix bg-white clearfix p-15 m-r-0 m-l-0">
        <div class="pull-right" >
            <button class="btn btn-default" type="reset"> <i class="fa fa-exclamation-circle"></i> Cancel</button> &nbsp;
            <button class="btn btn-success" type="submit"> <i class="fa fa-save"></i> Next </button>
        </div>
    </div>     
    
    <div class="p-20 col-md-12 ">
        <div class="clearfix m-b-sm m-l-sm"> <span class="h4 text-success"> Customer Details </span> </div>
        {{csrf_field()}}
        
        <div class="col-md-6 col-sm-12 m-auto ">

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> ID Type </label>
                <div class="col-md-8">
                    <select name="id_type" class="form-control">
                        <option value=""> &dash;&dash;&dash; Select ID Type &dash;&dash;&dash; </option>
                        <option value="CAC"> CAC </option> 
                    </select>
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Customer Category: </label>
                <div class="col-md-8">
                    <select name="customer_category" class="form-control">
                        <option value=""> &dash;&dash;&dash; Select Customer Category &dash;&dash;&dash; </option>
                        <option value="Private">Private</option>
                        <option value="Government">Government</option>
                        <option value="Public Limited">Public Limited</option>
                        <option value="NGO">NGO</option>
                        <option value="GOV PARASTATAL">GOV PARASTATAL</option>
                    </select>
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Customer Type: </label>
                <div class="col-md-8">
                    <select name="customer_type" class="form-control">
                        <option value=""> &dash;&dash;&dash; Select Customer Type &dash;&dash;&dash; </option>
                        <option value="Corporate">Corporate</option>
                    </select>
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Corporation Name: </label>
                <div class="col-md-8">
                    <input type="text" name="corporation_name" class="form-control" value="">
                </div>
            </div>


            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Corporation Type: </label>
                <div class="col-md-8">
                    <select name="corporation_type" class="form-control">
                        <option value=""> &dash;&dash;&dash; Select Corporation Type &dash;&dash;&dash; </option>
                        <option value="Corporate">Corporate</option>
                    </select>
                </div>
            </div>


            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Industry: </label>
                <div class="col-md-8">
                    <select name="industry" class="form-control">
                        <option value=""> &dash;&dash;&dash; Select Industry &dash;&dash;&dash; </option>
                        @foreach($industries as $industry)
                            <option value="{{ $industry->label }}">{{ $industry->label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Register Date: </label>
                <div class="col-md-8">
                    <input type="text" name="register_date" class="form-control datepicker" required="required" value="">
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Language: </label>
                <div class="col-md-8">
                    <select name="language" class="form-control">
                        <option value=""> &dash;&dash;&dash; Select Language &dash;&dash;&dash; </option>
                        <option value="English">English</option>
                    </select>
                </div>
            </div>


            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4 "> <i class="fa fa-asterisk text-danger f-s-12"></i> Issued By: </label>
                <div class="col-md-8 col-xs-12">
                    <select name="issued_by" class="form-control">
                        @foreach($operators as $operator)
                            <option value="{{ $operator->id }}" {{ $operator->id == Auth::id() ? 'selected' : '' }}> {{ $operator->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Package: </label>
                <div class="col-md-8 col-xs-12">
                    <select name="package" class="form-control">
                        <option value=""> &dash;&dash;&dash; Select Package &dash;&dash;&dash; </option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" > {{ $package->name .', '.$package->currency .$package->price }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            

        </div>

        <div class="col-md-6 col-sm-12">
            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Customer Sub Category: </label>
                <div class="col-md-8">
                    <select name="customer_sub_category" class="form-control">
                        <option value=""> &dash;&dash;&dash; Select Customer sub category &dash;&dash;&dash; </option>
                        <option value="Corporate">Corporate</option>
                    </select>
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Customer Grade: </label>
                <div class="col-md-8">
                    <select name="customer_grade" class="form-control">
                        <option value=""> &dash;&dash;&dash; Select Customer Grade &dash;&dash;&dash; </option>
                        <option value="Corporate">Corporate</option>
                    </select>
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Corporation Short Name </label>
                <div class="col-md-8">
                    <input type="text" name="corporation_short_name" class="form-control domain-input" value="">
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-12"> <i class="fa fa-asterisk text-danger f-s-12"></i> Corporation Link: </label>
                <div class="col-md-12">
                    <p class="f-s-16 text-success f-w-500 form-control"> <i class="fa fa-arrow-right"></i> <span class="domain-url-text f-s-16">{{ request()->root() }}/tenant/ </span></p>
                </div>
            </div>


            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4">Size Level: </label>
                <div class="col-md-8">
                    <select name="size_level" class="form-control">
                        <option value=""> &dash;&dash;&dash; Select Size Level &dash;&dash;&dash; </option>
                    </select>
                </div>
            </div>


            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4">Sub Industry: </label>
                <div class="col-md-8">
                    <select name="sub_industry" class="form-control">
                        <option value=""> &dash;&dash;&dash; Select Sub Industry &dash;&dash;&dash; </option>

                    </select>
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4">Register Captial: </label>
                <div class="col-md-8">
                    <input type="text" name="register_capital" class="form-control" value="">
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Nationality: </label>
                <div class="col-md-8">
                    <select name="nationality" class="form-control">
                        <option value=""> &dash;&dash;&dash; Select Nationality &dash;&dash;&dash; </option>
                        @foreach($countries as $country)
                            <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
             <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Payment Method: </label>
                <div class="col-md-8">
                    <select name="payment_method" class="form-control">
                        <option value=""> &dash;&dash;&dash; Select Payment Method &dash;&dash;&dash; </option>
                        <option value="Cash"> Cash </option>
                        <option value="Monthly Invoice"> Monthly Invoice </option>
                    </select>
                </div>
            </div>

        </div>

        <div class="col-md-12 clearfix"> 
            <hr class="horizonal-line-thick">
            <h3>CONTACT INFO  <span class="text-danger"> (At least one of the Home No./Office No./Mobile No./Fax No. Must be filled)</span></h3>
        </div>

        <div class="col-md-6 col-sm-12">
            
            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Email: </label>
                <div class="col-md-8">
                    <input type="email" name="email" class="form-control" value="">
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> First Name: </label>
                <div class="col-md-8">
                    <input type="text" name="firstname" class="form-control" value="">
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Last Name: </label>
                <div class="col-md-8">
                    <input type="text" name="lastname" class="form-control" value="">
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4">Home No.: </label>
                <div class="col-md-8 ">
                    <input type="text" class="form-control" name="home_no" value="" />
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4">Mobile No.: </label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="mobile_no" value="" />
                </div>
            </div>


        </div>


        <div class="col-md-6 col-sm-12">

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Middle/Father Name: </label>
                <div class="col-md-8">
                    <input type="text" name="middlename" class="form-control" value="">
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Title: </label>
                <div class="col-md-8">
                    <select name="title" class="form-control">
                        <option value=""> &dash;&dash;&dash; Select Title &dash;&dash;&dash; </option>
                        <option value="Mr"> Mr </option>
                        <option value="Mrs"> Mrs </option>
                    </select>
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> Address: </label>
                <div class="col-md-8">
                    <input type="text" name="address" class="form-control" value="">
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4"> <i class="fa fa-asterisk text-danger f-s-12"></i> State: </label>
                <div class="col-md-8">
                    <select name="state" class="form-control">
                        <option value="">Select State</option>
                        @foreach($states as $state)
                        <option value="{{ $state->name }}">{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4">Office No.: </label>
                <div class="col-md-8 ">
                    <input type="text" class="form-control" name="office_no" value="" />
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="form-label f-s-16 col-md-4">Fax No.: </label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="fax_no" value="" />
                </div>
            </div>


        </div>



    </div>

</form>

