<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\AccountCategory;

class AccountCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
		DB::statement('TRUNCATE TABLE ACCOUNT_CATEGORIES CASCADE;');

		$list = [
			'Analyst',
			'Competitor',
			'Consultant',
			'Customer',
			'Dead',
			'Other',
			'Personal',
			'Press',
			'Prospect',
			'Supplier',
			'Suspect'
		];

		foreach ($list as $value) {
			$this->save($value);
		}

		
        Model::reguard();
    }

    private function save($data){
    	AccountCategory::create([
    		'name' => $data,
    		'label' => $data,
    		]);

    }
}
