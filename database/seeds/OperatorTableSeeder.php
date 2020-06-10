<?php

use Illuminate\Database\Seeder;
use App\Models\Operator;
use Illuminate\Database\Eloquent\Model;

class OperatorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        		
    	Model::unguard();
		DB::statement('TRUNCATE TABLE OPERATORS CASCADE;');

		Operator::create([
			'firstname' => 'Abiodun',
			'lastname' =>  'Adeyinka',
			'email' => 'aadeyinka@techmadeeazy.com',
			'password' => bcrypt('password'),
			'job_title' => 'Software Engineer',
            'sadmin' => 1,

		]); 

        Model::reguard();

    }
}
