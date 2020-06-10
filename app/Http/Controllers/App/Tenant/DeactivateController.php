<?php

namespace App\Http\Controllers\App\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeactivateController extends Controller
{
	public function __construct(){
		
        $this->middleware(['auth:web', 'tenant']);
	}

    public function index($domain, Request $request){

    } 

    public function form($domain, Request $request){

    	return view('app.tenant.deactivate');
    }

}
