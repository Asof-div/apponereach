@extends('layouts.auth')

@section('content')

    <div class="register register-with-news-feed clearfix">

        <div class="news-feed bg-silver pull-left overflow-y">
            <div class="news-image m-t-100">

                <div class="col-md-12">
                        
                    <div class="panel panel-white" >
                        <div class="panel-body overflow-y" >
                            
                            <div class="cd-pricing-container cd-has-margins">

                                <ul class="cd-pricing-list cd-bounce-invert overflow-y height-600">
                                    @foreach($packages as $package)
                                        <li>
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
                                                            <li><em>{{ $package->msisdn_limit }}</em> CUG Number </li>
                                                            <li><em>{{ $package->user_limit }}</em> User</li>
                                                             @foreach($addons as $feature)

                                                                <li class="{{ $package->addons->filter(function($item) use ($feature){ return $item->id == $feature->id; } )->first() ? "check" : "uncheck" }}">{{ $feature->label }}</li>

                                                            @endforeach

                                                        </ul>
                                                    </div>
                                                </li>
 
                                            </ul>
                                        </li>
                                    @endforeach
                    
                                </ul>
                            </div>

                        </div>
                    </div>        

                </div>

                <div class="col-md-12">
                    <div class="panel panel-white">
                        <div class="panel-body overflow-y">
                            <p class="f-s-15"> Please select your package </p>
                            <div class="table-responsive height-300">
                                <table class="table table-hover table-striped cd-pricing-header">
                                    <tr>
                                        <th></th>
                                        <th> 1 MONTH </th>
                                        <th> 3 MONTH </th>
                                        <th> 6 MONTH </th>
                                        <th> 1 YEAR </th>
                                    </tr>

                                    @foreach($packages as $package)

                                        <tr>
                                            <th> <h2 class="m-0"> {{ $package->name }} </h2> </th>
                                            <td class="text-left">
                                                <label class="f-s-16">
                                                    <input class="package" type="radio" name="package" value="{{ sprintf("%02d",1).$package->name }}"> 
                                                    <span class="">&#x20A6;</span>
                                                    <span class="">{{ number_format($package->price) }}</span>
                                                </label>
                                            </td>
                                            <td class="text-left">
                                                <label class="f-s-16">
                                                    <input class="package" type="radio" name="package" value="{{ sprintf("%02d",3).$package->name }}"> 
                                                    <span class="">&#x20A6;</span>
                                                    <span class="">{{ number_format($package->price * 3) }}</span>
                                                </label>
                                            </td>
                                            <td class="text-left">
                                                <label class="f-s-16">
                                                    <input class="package" type="radio" name="package" value="{{ sprintf("%02d",6).$package->name }}"> 
                                                    <span class="">&#x20A6;</span>
                                                    <span class="">{{ number_format($package->price * 6) }}</span>
                                                </label>
                                            </td>
                                            <td class="text-left">
                                                <label class="f-s-16">
                                                    <input class="package" type="radio" name="package" value="{{ sprintf("%02d",12).$package->name }}"> 
                                                    <span class="">&#x20A6;</span>
                                                    <span class="">{{ number_format($package->price * 12) }}</span>
                                                </label>
                                            </td>

                                        </tr>

                                    @endforeach

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="right-content clearfix bg-white" >

            <h1 class="register-header">
                Sign Up
                <small>Create your Cloud PBX Account. It’s free and always will be.</small>
            </h1>

            @include('partials.flash_message')
            @include('partials.validation')

            <div class="register-content clearfix">
                <form action="{{ url('register') }}" method="POST" class="margin-bottom-0">
                    {{ csrf_field() }}
                    <label class="control-label ">Name <i class="fa fa-asterisk"></i></label>
                    <input type="hidden" name="package" value="" id="package_signup">
                    <div class="row row-space-10">
                        <div class="col-md-6 m-b-15">
                            <input type="text" class="form-control {{ $errors->has('firstname') ? ' has-error' : '' }}" placeholder="First name" name="firstname" value="{{ old('firstname') }}" required="required" />
                            @if ($errors->has('firstname'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('firstname') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6 m-b-15">
                            <input type="text" class="form-control {{ $errors->has('lastname') ? ' has-error' : '' }}" placeholder="Last name" name="lastname" value="{{ old('lastname') }}" required="required" />
                            @if ($errors->has('lastname'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('lastname') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <label class="control-label">Email <i class="fa fa-asterisk"></i></label>
                    <div class="row m-b-15">
                        <div class="col-md-12">
                            <input type="email" class="form-control {{ $errors->has('email') ? ' has-error' : '' }}" placeholder="Email address" name="email" value="{{ old('email') }}" required="required"  />
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <label class="control-label">Billing Phone Number <i class="fa fa-asterisk"></i> </label>
                    <div class="row m-b-15">
                        <div class="col-md-3">
                            <input type="text" class="form-control {{ $errors->has('code') ? ' has-error' : '' }}" placeholder="Code" name="code" value="{{ old('code') }}" required="required" />
                            @if ($errors->has('code'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('code') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control {{ $errors->has('telephone') ? ' has-error' : '' }}" placeholder="Telephone" name="telephone" value="{{ old('telephone') }}" required="required" />
                            @if ($errors->has('telephone'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('telephone') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="row m-b-15">
                        <div class="col-md-8">
                            <label class="control-label">Company Name <i class="fa fa-asterisk"></i></label>
                            <input type="text" class="form-control {{ $errors->has('company_name') ? ' has-error' : '' }}" placeholder="Company Name" name="company_name" value="{{ old('company_name') }}" />

                            @if ($errors->has('company_name'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('company_name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">Number Of Users</label>
                            <select class="form-control {{ $errors->has('number_of_users') ? ' has-error' : '' }}" name="number_of_users" placeholder="Number Of Users">
                                <option value="5" {{ old('number_of_users') == '5' ? 'selected' : '' }}>1 - 5</option>
                                <option value="10" {{ old('number_of_users') == '10' ? 'selected' : '' }}>5 - 10</option>
                                <option value="20" {{ old('number_of_users') == '20' ? 'selected' : '' }}>11 - 20</option>
                                <option value="50" {{ old('number_of_users') == '50' ? 'selected' : '' }}>21 - 50</option>
                                <option value="100" {{ old('number_of_users') == '100' ? 'selected' : '' }}>51 - 100</option>
                                <option value="500" {{ old('number_of_users') == '500' ? 'selected' : '' }}>101 - 500</option>
                                <option value="1000" {{ old('number_of_users') == '1000' ? 'selected' : '' }}>501 - 1000</option>
                                <option value="1001+" {{ old('number_of_users') == '1001+' ? 'selected' : '' }}>1001 and Above</option>
                            </select>
                        </div>
                    </div>

                    <label class="control-label"> Company Domain Name <i class="fa fa-asterisk"></i></label>
                    <div class="row row-space-10">
                        <div class="col-md-12 m-b-0">
                            <p class="f-s-15 text-primary f-w-500"> <i class="fa fa-arrow-right"></i> <span class="domain-url-text">{{ request()->root() }}/tenant/ </span></p>
                        </div>
                        <div class="col-md-12 m-b-15">
                            <input type="text" class="form-control domain-input {{ $errors->has('domain_name') ? ' has-error' : '' }}" placeholder="onereach" name="domain" value="{{ old('domain') }}" />
                            <input type="hidden" class="domain-input-result" name="domain_name" value="{{ old('domain_name') }}" />
                            @if ($errors->has('domain_name'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('domain_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    
                    <label class="control-label">Street Address</label>
                    <div class="row row-space-10">
                        <div class="col-md-6 m-b-15">
                            <input type="text" class="form-control {{ $errors->has('street_address1') ? ' has-error' : '' }}" placeholder="Street Address 1" name="street_address1" value="{{ old('street_address1') }}" required="required" />
                            @if ($errors->has('street_address1'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('street_address1') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6 m-b-15">
                            <input type="text" class="form-control {{ $errors->has('street_address2') ? ' has-error' : '' }}" placeholder="Street Address 2" name="street_address2" value="{{ old('street_address2') }}" />
                            @if ($errors->has('street_address2'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('street_address2') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    <div class="row m-b-15">
                        <div class="col-md-6">
                            <label class="control-label ">City</label>
                            <input type="text" class="form-control {{ $errors->has('city') ? ' has-error' : '' }}" placeholder="City" name="city" value="{{ old('city') }}" required="required" />
                            @if ($errors->has('city'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('city') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="control-label ">State</label>
                            <input type="text" class="form-control {{ $errors->has('state') ? ' has-error' : '' }}" placeholder="State" name="state" value="{{ old('state') }}" required="required" />
                            @if ($errors->has('state'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('state') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <label class="control-label ">Country</label>
                    <div class="row m-b-15">
                        <div class="col-md-12">
                            <input type="text" class="form-control {{ $errors->has('country') ? ' has-error' : '' }}" placeholder="Country" name="country" required="required" value="{{ old('country') }}">
                            @if ($errors->has('country'))
                                <span class="help-block">
                                    <strong class="text-danger">{{ $errors->first('country') }}</strong>
                                </span>
                            @endif
                        </div>                
                    </div>
                    
                    <div class="row m-b-15 f-s-15">
                        <span class="text-danger">NB: </span> Select your prefered <em class="f-w-700">Package</em> and <em class="f-w-700">Duration</em> before you submit. 
                    </div>
                    <div class="register-buttons">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Sign Up</button>
                    </div>
                    <div class="m-t-20 m-b-40 p-b-40">
                        Already a member? Click <a href="{{ url('login') }}">here</a> to login.
                    </div>
                    <hr />
                    <p class="text-center text-inverse">
                        &copy; Cloud PBX All Right Reserved 2018
                    </p>
                </form>
            </div>

        </div>

    </div>


@endsection

@section('extra-script')

    @include('partials.fail')

    <script type="text/javascript">
        let root_url = "{{ request()->root() }}/tenant/";
        $('body').on('input', '.domain-input', function(){

            let domain = $(this).val();
            domain = domain.replace(/[#\/\s*$@^&%()\\?\[\]\|\{\}\~\`]/g,'_');
            domain = domain.toLowerCase();

            $('.domain-url-text').text(root_url + domain);
            $('.domain-input-result').val(domain);
            $('.domain-input').val(domain);

        });

        $('body').on('click', 'input[type="radio"].package', function(){

            $('#package_signup').val($(this).val());
            // alert($(this).val());

        });

    </script>

@endsection


@section('extra-css')
    
    <style type="text/css">

        .register, .register-with-news-feed{
            background-color: #fff !important;
            height: 100% !important;
        }
        .has-error{
            background: #ffdedd !important;
            border-color: #ff5b57 !important;
        }
        li.check::before {
            font-family: "FontAwesome";
            content: "\f00c";
            font-size: 1.3rem;
            color: #33c4b6;
            margin-right: 3px;
        }
        li.uncheck::before {
            font-family: "FontAwesome";
            content: "\f00d";
            font-size: 1.3rem;
            color: red;
            margin-right: 3px;
        }
        .overflow-y{
            overflow-y: auto !important;
        }

    </style>    

@endsection
