<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	Model::unguard();
		DB::statement('TRUNCATE TABLE ADMINS CASCADE;');


		Admin::create([
			'firstname' => 'Abiodun',
			'lastname' =>  'Adeyinka',
			'email' => 'abiodun.adeyinka@telvida.com',
			'password' => bcrypt('password'),
			'job_title' => 'Software Engineer',

			]); 
        Model::reguard();
    }
}
