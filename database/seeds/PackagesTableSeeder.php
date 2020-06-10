<?php

use Illuminate\Database\Seeder;
use App\Models\Package;
use Illuminate\Database\Eloquent\Model;


class PackagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Model::unguard();
        DB::statement('TRUNCATE TABLE PACKAGES CASCADE;');

        Package::create([
            'name' => 'Free',
            'currency' => 'NGN',
            'price' => '0',
            'description' => '5 Days Trail',
            'addon_binary' => 0,
            'msisdn_limit' => 3,
            'extension_limit' => 3,
            'user_limit' => 3,
            'annually' => '0',
        ]);

    	Package::create([
			'name' => 'Basic',
			'currency' => 'NGN',
			'price' => '1500',
			'description' => 'Basic',
            'addon_binary' => 1,
            'msisdn_limit' => 3,
            'extension_limit' => 3,
            'user_limit' => 3,
            'annually' => '16500',
    		]);

    	Package::create([
			'name' => 'Standard',
			'currency' => 'NGN',
			'price' => '3000',
			'description' => 'Standard',
			'addon_binary' => 1,
            'msisdn_limit' => 5,
            'extension_limit' => 5,
            'user_limit' => 5,
            'annually' => '33000',
    		]);

        Package::create([
            'name' => 'Executive',
            'currency' => 'NGN',
            'price' => '7500',
            'description' => 'Executive',
            'addon_binary' => 1,
            'msisdn_limit' => 10,
            'extension_limit' => 10,
            'user_limit' => 10,
            'annually' => '82500',
            ]);
        
        Model::reguard();
    }
}
