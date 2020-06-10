@extends('mails.template.mail_template')

@section('greetings')
  Hello, {{ $user->firstname ." ". $user->lastname }}
@endsection

@section('title')
  Registration Completed
@endsection

@section('body')

  <div align="" class="center">
    <div style="text-align: center; clear: both;" >
    Your username is : {{ $user->email }} <br/>

    Your account is : {{ $user->tenant ? $user->tenant->domain : 'N/A' }} <br/>
    </div>

    <div>
      <p>PLEASE MAKE SURE YOU CHANGE YOUR b PASSWORD AS SOON AS YOU LOG IN.</p>
    </div>

    <div align="center" class="button-container center" style="clear: both; margin-top: 15px;">
      
      <a class="btn" target="blank" href="{{ url('login') }}">
        <span>ACTIVATE MY ACCOUNT </span>
      </a>

    </div>
  </div>

@endsection

