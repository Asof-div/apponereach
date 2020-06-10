<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Operator\PilotNumber;
use Carbon\Carbon;

class PilotNumberReleaserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pilotnumber:release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release Locked Pilot Numbers after locked time expires';

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
        $this->flushPilotNumbers();
    }

    private function flushPilotNumbers(){


        $pilot_numbers = PilotNumber::where('purchased', 0)->where('available',0)->orderBy('release_time', 'desc')->get();  

        foreach ($pilot_numbers as $pilot) {
            
            $now = Carbon::now();
            $release = $pilot->release_time;
            if( is_null($release) ){
                
                $this->cancelBilling($pilot->billing_id);
                $pilot->update(['tenant_id' => null, 'available' => 1, 'release_time' => null, 'billing_id' => null, 'status' => 'unallocated']);

            }      
            elseif($now->gte($release)){

                $this->cancelBilling($pilot->billing_id);
                $pilot->update(['tenant_id' => null, 'available' => 1, 'release_time' => null, 'billing_id' => null, 'status' => 'unallocated']);
            }else{

                if($release->diffInMinutes($now) < 1){
                    $this->cancelBilling($pilot->billing_id);
                    $pilot->update(['tenant_id' => null, 'available' => 1, 'release_time' => null, 'billing_id' => null, 'status' => 'unallocated']);
                    
                }

            }



        }

    }

    public function cancelBilling($id){
        $billing = Billing::find($id);

        if($billing){
            $items = [];
                foreach( json_decode($billing->ordered_items, true) as $item ){
                    if($item['type'] == 'DID'){
                        $item['status'] = 0;
                    }
                    $items[] = $item;
                }

                $billing->update([ 'status' => 'Cancelled', 'ordered_items' => json_encode($items), 'paid_date' => '']);

        }

    }


}
