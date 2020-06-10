<?php

namespace App\Repos;

use App\Models\User;
use App\Models\Call;
use App\Models\Extension;
use App\Models\Contact;

class UserRepository
{
	public function findByPhonenumber(string $phonenumber)
    {
        return User::where('phonenumber', $phonenumber)->first();
    }

    public function findByExtension(int $tenant_id, string $phonenumber)
    {	
    	$exten = Extension::where('tenant_id', $tenant_id)->where('number', $phonenumber)->first();
    	if($exten){

    		return $exten->user_id;
    	}

        return null;
    }

    public function bindCallByExtension($details, Call $call)
    {   
        $tenant_id = $details['tenant_id'];
        $exten = Extension::where('tenant_id', $tenant_id)->where('number', $details['caller'])->first();
        if($exten){
    
            $exten->user->calls()->save($call, ['call_direction' => 'Outbound']);
        }

        $exten = Extension::where('tenant_id', $tenant_id)->where('number', $details['callee'])->first();
        if($exten){
    
            $exten->user->calls()->save($call, ['call_direction' => 'Inbound']);
        }

    }

    public function bindCallByContact($details, Call $call)
    {   
        $tenant_id = $details['tenant_id'];
        $exten = Extension::where('tenant_id', $tenant_id)->where('number', $details['caller'])->first();
        if($exten){
    
            $exten->user->calls()->save($call, ['call_direction' => 'Outbound']);
        }

        $contact = Contact::where('tenant_id', $tenant_id)->where('number', $details['callee'])->first();
        if($contact){
    
            $call->update(['contact_id' => $contact->id]);
        }
        
    }

}