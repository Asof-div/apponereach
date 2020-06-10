<?php

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Model;

class VoucherTableSeeder extends Seeder
{

    public function run()
    {

        Model::unguard();
        DB::statement('TRUNCATE TABLE VOUCHERS CASCADE;');
      	$size = 12;
        
        foreach ( range(0, 1000) as $number) {
        	$code = strtoupper(substr( md5( time().sprintf("%06X", rand(000000, 999999) ) ), 0, $size) );
            $this->save( $code );
        }

        Model::reguard();
    }


    function save($code){

    	$voucher = Voucher::where('voucher_code', $code)->first();

    	if(!$voucher){

    		Voucher::create([
    			'voucher_code' => $code,
    			'voucher_type' => 'line',
    			'value' => 20000.00,
    			'price' => 20000.00 ,
    			]);
    	}

    }


}
