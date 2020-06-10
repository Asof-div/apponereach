<?php

use Illuminate\Database\Seeder;
use App\Models\Operator\PilotNumber;
use App\Models\LineType;
use Illuminate\Database\Eloquent\Model;

class PilotNumberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    protected $type = ['Local', 'Toll Free'];

    public function run()
    {

        Model::unguard();
        DB::statement('TRUNCATE TABLE LINE_TYPES CASCADE;');
        DB::statement('TRUNCATE TABLE PILOT_NUMBERS CASCADE;');
      
        
        $regular = LineType::create([
            'name' => 'regular',
            'label' => 'Regular',
            'price' => 20000.00,
            ]);

        $vanity = LineType::create([
            'name' => 'vanity',
            'label' => 'Vanity',
            'price' => 30000.00,
            ]);

        $this->getNumber('07001237000', $regular->id);
        $this->getNumber('07001237001', $regular->id);

        foreach ( range(0, 50) as $number) {
            // $this->getNumber('0800221'.sprintf("%04d", $number), $regular->id);
            $this->getNumber('08042413'.sprintf("%03d", $number), $regular->id);
        }

        Model::reguard();
    }


    function getNumber($number, $id){

    	$pilot = PilotNumber::where('number', $number)->first();

    	if(!$pilot){

    		PilotNumber::create([
    			'number' => $number,
    			'available' => 1,
    			'source' => 'Operator',
    			'type_id' => $id ,
    			]);
    	}
    }



}
