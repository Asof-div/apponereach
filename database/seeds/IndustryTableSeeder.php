<?php

use Illuminate\Database\Seeder;
use App\Models\Industry;
use Illuminate\Database\Eloquent\Model;

class IndustryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	
    	Model::unguard();
		DB::statement('TRUNCATE TABLE INDUSTRIES CASCADE;');

		$list = [
		 	'Oil and Gas',
			'Manufacturing / FMCG',
			'Healthcare',
			'Legal',
			'Banking',
			'Education',
			'Media',
			'Insurance',
			'Financial Services',
			'Associations',
			'Others',
			'Transportation',
			'Technology',
			'Engineering/Construction',
			'Consultancy',
			'Government',
			'Telecommunication',
			'Consumer Goods',
			'Facility Management',
			'Real Estate',
		];

		foreach ($list as $value) {
			$this->saveIndustry($value);
		}

		
        Model::reguard();
    }

    private function saveIndustry($data){
    	Industry::create([
    		'name' => $data,
    		'label' => $data,
    		'description' => $data
    		]);
    }


}
