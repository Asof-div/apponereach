<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\AccountSource;

class AccountSourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
		DB::statement('TRUNCATE TABLE ACCOUNT_SOURCES CASCADE;');

		$list = [
		 	'Advertisement',
			'Email',
			'Mailshot',
			'Pay Per Click',
			'Press',
			'Refferal',
			'Social',
			'Telephone',
			'Web Search',
			'Web site',
			'Word of Mouth'
		];

		foreach ($list as $value) {
			$this->save($value);
		}

		
        Model::reguard();
    }

    private function save($data){
    	AccountSource::create([
    		'name' => $data,
    		'label' => $data
    		]);

    }
}
