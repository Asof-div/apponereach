@extends('layouts.auth')


@section('content')

    <div class="container ">

        <div class="col-lg-6 col-md-8 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-2 col-sm-offset-2">
            <div class="panel panel-default m-t-50" >

                <div class="panel-body">

                    <div class="">
                        <h4> Login </h4>
                    </div>
                    <hr class="horizonal-line clearfix">

                    @include('partials.flash_message')

                    <form action="{{ route('login') }}" method="POST" class="margin-bottom-0">
                        {{ csrf_field() }}
                 
                        <label class="control-label">Email</label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <input type="email" class="form-control big-form-control {{ $errors->has('email') ? ' has-error' : '' }}" placeholder="Email address" name="email" value="{{ old('email') }}" required="required"  />
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <label class="control-label">Password </label>
                        <div class="row m-b-15">
                            <div class="col-md-12">
                                <input type="password" class="form-control big-form-control {{ $errors->has('password') ? ' has-error' : '' }}" placeholder="password" name="password" value="{{ old('password') }}" required="required" />
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <label class="control-label">Account </label>
                        <div class="row row-space-10">
                            <div class="col-md-12 m-b-15">
                                <input type="text" class="form-control big-form-control {{ $errors->has('account') ? ' has-error' : '' }}" placeholder="Account Name" name="account" value="{{ old('account') }}" required="required" />
                                @if ($errors->has('account'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('account') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        
                        <div class="login-buttons">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">Login</button>
                        </div>
                        <div class="m-t-20 m-b-40 p-b-40">
                            <div class="col-md-6 text-justify">
                                Not a member yet? Click <a href="{{ url('register') }}">here</a> to register
                            </div>
                            <div class="col-md-6 text-center">
                                <a class="btn btn-link" href="{{ route('password.request') }}">Forgot Your Password?</a>
                            </div>
                        </div>
                       
                    </form>

                </div>
                
            </div>
        </div>

    </div>
@endsection

@section('extra-script')

    @include('partials.fail')

@endsection

@section('extra-css')
    
    <style type="text/css">
        .login, .login-with-news-feed{
            background-color: #fff !important;
            height: 100% !important;
        }
        .has-error{
            background: #ffdedd !important;
            border-color: #ff5b57 !important;
        }
    </style>    

@endsection

