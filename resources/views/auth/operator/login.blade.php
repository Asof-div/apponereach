@extends('layouts.operator')

@section('content')

    
    <div class="panel" >
        <div class="panel-heading">
            ADMIN LOGIN 
        </div>
        <div class="panel-body">
            
            <div class="col-md-3 center ">
                <div class="login-box ">
                    
                    <p class="text-center m-t-md">Please login into your account.</p>
                    
                    <form class="form-horizontal" method="POST" autocomplete="off" action="{{ route('operator.login.do') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-12 ">E-Mail Address</label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control form-control-line" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-12 ">Password</label>

                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 ml-20">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-block">
                                    Login
                                </button>

                            </div>
                        </div>
                        <div class="form-group">
                            <p class="text-center m-t-xs text-sm">Unautorized access not allowed. This page is only for staff.</p>

                            <a class="btn btn-link" href="{{ route('operator.password.request') }}">
                                    Forgot Your Password?
                                </a>
                        </div>
                    </form>
                
                </div>
            </div>

        </div>

    </div>

@endsection

@section('extra-css')
    <style type="text/css">
        #main-wrapper{
            margin: 40px;
            font-size: 1.2em !important;
        }

    </style>
@endsection
