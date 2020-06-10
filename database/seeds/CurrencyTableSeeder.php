<?php

use Illuminate\Database\Seeder;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;


class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Model::unguard();
        DB::statement('TRUNCATE TABLE CURRENCIES CASCADE;');

        Currency::create([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '&#x24;'
        ]);

        Currency::create([
            'name' => 'Great Britain Pound',
            'code' => 'GBP',
            'symbol' => '&#xa3;'
        ]);

        Currency::create([
            'name' => 'EURO',
            'code' => 'EUR',
            'symbol' => '&#x20AC;',
        ]);

        Currency::create([
            'name' => 'Nigeria Naira',
            'code' => 'NGN',
            'symbol' => '&#x20A6;',
            'default' => 1
        ]);
 
        Model::reguard();
    }

    private function saveData($data){
    	
    }
}
