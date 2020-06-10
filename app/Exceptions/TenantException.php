<?php

namespace App\Exceptions;

use Exception;
use App\Events\AppError;
use Illuminate\Http\Request;

class TenantException extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }

    public function report(){

    	// \Log::log('info', serialize($this->getTraceAsString()) );

        event(new AppError($this->getTraceAsString()));    

    }

    public function render(Request $request){

    	// \Log::log('info', $request->all());
    	return view()->make('errors.tenant');
    }
    
}