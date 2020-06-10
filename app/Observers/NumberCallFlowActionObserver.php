<?php

namespace App\Observers;
use App\Models\Number;
use App\Models\CallFlow;
use App\Models\CallFlowAction as FlowAction;

class NumberCallFlowActionObserver
{

    /**
     * Listen to the User created event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function creating(Number $number)
    {
        

    }


    /**
     * Listen to the User created event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(Number $number)
    {
        


    }


    /**
     * Listen to the User created event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(Number $number)
    {

        



    }



    /**
     * Listen to the User deleting event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleting(Number $number)
    {
        
        
    }

}
