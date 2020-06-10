@extends('layouts.error')

@section('content')

    <div class="error-code m-b-10">Account does not exist <i class="fa fa-warning"></i></div>
    <div class="error-content">
        <div class="error-message">We couldn't find it...</div>
        <div class="error-desc m-b-20">
            The page you're looking for doesn't exist. <br />
        </div>
        <div>
            <a href="{{ route('login') }}" class="btn btn-success">Login</a>
        </div>
    </div>

@endsection