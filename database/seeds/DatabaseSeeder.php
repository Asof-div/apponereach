<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(AdminsTableSeeder::class);
        $this->call(OperatorTableSeeder::class);
        $this->call(PackagesTableSeeder::class);
        $this->call(PilotNumberTableSeeder::class);
        $this->call(AddonsTableSeeder::class);
        $this->call(OperatorTableSeeder::class);
        $this->call(IndustryTableSeeder::class);
        $this->call(CurrencyTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(StateTableSeeder::class);
        $this->call(UserPermissionTableSeeder::class);
        $this->call(OperatorPermissionTableSeeder::class);
        $this->call(AdminPermissionTableSeeder::class);
        $this->call(IncidentTableSeeder::class);
        $this->call(AccountCategoryTableSeeder::class);
        $this->call(AccountSourceTableSeeder::class);
        $this->call(CallRatesTableSeeder::class);
        $this->call(PaymentTermTableSeeder::class);
        $this->call(VoucherTableSeeder::class);
        Model::reguard();
    }
}
