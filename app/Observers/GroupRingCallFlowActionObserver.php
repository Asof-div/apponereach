<?php

namespace App\Observers;
use App\Models\GroupCall;
use App\Models\CallFlow;
use App\Models\CallFlowAction as FlowAction;

class GroupRingCallFlowActionObserver
{

    /**
     * Listen to the User created event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function creating(GroupCall $group)
    {
       

    }


    /**
     * Listen to the User created event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(GroupCall $group)
    {
       

    }


    /**
     * Listen to the User created event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(GroupCall $group)
    {

       



    }



    /**
     * Listen to the User deleting event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleting(GroupCall $group)
    {
       

    }

    public function deleted(GroupCall $group){

        // \DB::statement('SET FOREIGN_KEY_CHECKS=1');


    }

}
