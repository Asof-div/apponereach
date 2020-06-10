<?php

namespace App\Http\Controllers\App\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
   	/**
   	*	Landing Page For An Administrator
   	*
   	*/

   	public function __construct(){

   		$this->middleware('guest:operator');
   	}

   	public function index(){

   		// return view('app.operator.index');
         return redirect()->route('operator.login');
   	}
      
}
