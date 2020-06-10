@extends('layouts.error')

@section('content')

    <div class="error-code m-b-10">404 <i class="fa fa-warning"></i></div>
    <div class="error-content">
        <div class="error-message">We couldn't find it...</div>
        <div class="error-desc m-b-20">
            The page you're looking for doesn't exist. <br />
            Perhaps, there pages will help find what you're looking for.
        </div>
        <div>
            <a href="{{ redirect()->back() }}" class="btn btn-success">Go Back to Home Page</a>
        </div>
    </div>

@endsection