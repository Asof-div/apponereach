<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class CancelOverdueOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }

    private function cancelOverdue(){


        $pilot_numbers = PilotNumber::where('purchased', 0)->where('available',0)->orderBy('release_time', 'desc')->get();  

        foreach ($pilot_numbers as $pilot) {
            
            $now = Carbon::now();
            $release = $pilot->release_time;
            if( is_null($release)){
                
                $pilot->update(['tenant_id' => null, 'available' => 1, 'release_time' => null, 'billing_id' => null, 'status' => 'unallocated']);

            }      
            elseif($now->gte($release)){

                $pilot->update(['tenant_id' => null, 'available' => 1, 'release_time' => null, 'billing_id' => null, 'status' => 'unallocated']);
            }

        }

    }

}
