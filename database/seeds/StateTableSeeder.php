<?php

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Eloquent\Model;

class StateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        	
    	Model::unguard();
		DB::statement('TRUNCATE TABLE STATES CASCADE;');

		$nga = Country::where('iso3', 'NGA')->where('phonecode', '234')->first();

		$state_list = [
			[ 'name' => 'Abia', 'code' => 'AB'],
			[ 'name' => 'Adamawa', 'code' => 'AD'],
			[ 'name' => 'Akwa ibom', 'code' => 'AK'],
			[ 'name' => 'Anambra', 'code' => 'AN'],
			[ 'name' => 'Bauchi', 'code' => 'BA'],
			[ 'name' => 'Bayelsa', 'code' => 'BY'],
			[ 'name' => 'Benue', 'code' => 'BN'],
			[ 'name' => 'Borno', 'code' => 'BO'],
			[ 'name' => 'Cross river', 'code' => 'CR'],
			[ 'name' => 'Delta', 'code' => 'DT'],
			[ 'name' => 'Ebonyi', 'code' => 'EB'],
			[ 'name' => 'Edo', 'code' => 'ED'],
			[ 'name' => 'Ekiti', 'code' => 'EK'],
			[ 'name' => 'Enugu', 'code' => 'EN'],
			[ 'name' => 'Fct', 'code' => 'FC'],
			[ 'name' => 'Gombe', 'code' => 'GM'],
			[ 'name' => 'Imo', 'code' => 'IM'],
			[ 'name' => 'Jigawa', 'code' => 'JG'],
			[ 'name' => 'Kaduna', 'code' => 'KD'],
			[ 'name' => 'Kano', 'code' => 'KN'],
			[ 'name' => 'Katsina', 'code' => 'KT'],
			[ 'name' => 'Kebbi', 'code' => 'KB'],
			[ 'name' => 'Kogi', 'code' => 'KG'],
			[ 'name' => 'Kwara', 'code' => 'KW'],
			[ 'name' => 'Lagos', 'code' => 'LA'],
			[ 'name' => 'Nassarawa', 'code' => 'NS'],
			[ 'name' => 'Niger', 'code' => 'NG'],
			[ 'name' => 'Ogun', 'code' => 'OG'],
			[ 'name' => 'Ondo', 'code' => 'OD'],
			[ 'name' => 'Osun', 'code' => 'OS'],
			[ 'name' => 'Oyo', 'code' => 'OY'],
			[ 'name' => 'Plateau', 'code' => 'PL'],
			[ 'name' => 'Rivers', 'code' => 'RV'],
			[ 'name' => 'Sokoto', 'code' => 'SO'],
			[ 'name' => 'Taraba', 'code' => 'TR'],
			[ 'name' => 'Yobe', 'code' => 'YB'],
			[ 'name' => 'Zamfara', 'code' => 'ZM']
		];

		if($nga){
			foreach ($state_list as $value) {
				$this->saveState($nga->id, $value);
			}
 		}
        Model::reguard();
    }

    private function saveState($id, $data){
    	State::create([
    		'country_id' => $id,
    		'name' => $data['name'],
    		'code' => $data['code']
    		]);
    }
}
