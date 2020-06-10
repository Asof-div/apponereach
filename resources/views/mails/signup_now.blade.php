@extends('mails.template.mail_template')

@section('greetings')
	Hello, Adeyinka Abiodun
@endsection

@section('title')
	Registration Completed
@endsection

@section('body')

	<div align="" class="center">
		<div style="text-align: center; clear: both;" >
		Your username is : adeyinkab24@gmail.com <br/>

		Your password is : [0OR^C <br/>

		Your account is : access <br/>
		</div>

		<div style="text-align: center; clear: both;">
			<p>PLEASE MAKE SURE YOU CHANGE YOUR PASSWORD <br/> AS SOON AS YOU LOG IN.</p>
		</div>

		<div align="center" class="button-container center" style="clear: both; margin-top: 15px;">
			
			<a class="btn" href="{{ url('login') }}">
				<span >ACTIVATE MY ACCOUNT </span>
			</a>

		</div>
	</div>

@endsection

