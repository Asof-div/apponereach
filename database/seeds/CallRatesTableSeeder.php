<?php

use App\Models\CallRate;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class CallRatesTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		Model::unguard();
		DB::statement('TRUNCATE TABLE CALL_RATES CASCADE;');

		$nga = Country::where('iso3', 'NGA')->where('phonecode', '234')->first();
		$usa = Country::where('iso3', 'USA')->where('phonecode', '1')->first();

		CallRate::create(['country_id' => $nga->id, 'phonecode' => $nga->phonecode, 'rate' => '12.5']);// Nigeria
		CallRate::create(['country_id' => $usa->id, 'phonecode' => $usa->phonecode, 'rate' => '8']);// US
		CallRate::create(['phonecode'  => '*', 'rate'  => '8', 'default'  => true]);// US

		Model::reguard();
	}
}
