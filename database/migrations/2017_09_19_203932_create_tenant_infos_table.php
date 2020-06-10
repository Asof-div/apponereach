<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->index()->unsigned();
            $table->string('billing_method')->default('prepay');
            $table->string('logo')->nullable();
            $table->integer('updated_by');
            $table->string('user_limit')->nullable();
            $table->string('custom_template')->nullable();
            $table->string('url_link')->nullable();
            $table->text('template')->nullable();


            $table->string('card_number')->nullable();
            $table->string('card_month')->nullable();
            $table->string('card_year')->nullable();
            $table->string('card_cvv')->nullable();
            $table->string('extra_number')->nullable();
            $table->boolean('number_lock')->default(0);

            $table->string('type')->nullable();
            $table->string('id_type')->nullable();
            $table->string('id_no')->nullable();
            $table->string('customer_category')->nullable();
            $table->string('customer_sub_category')->nullable();
            $table->string('corporation_name')->nullable();
            $table->string('corporation_short_name')->nullable();
            $table->string('corporation_type')->nullable();
            $table->string('customer_type')->nullable();
            $table->string('customer_grade')->nullable();
            $table->string('industry')->nullable();
            $table->string('sub_industry')->nullable();
            $table->string('size_level')->nullable();
            $table->timestamp('registration_date')->nullable();
            $table->string('register_capital')->nullable();
            $table->timestamp('activation_date')->nullable();
            $table->string('language')->nullable();
            $table->string('country_code')->nullable();
            $table->string('home_no')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('office_no')->nullable();
            $table->string('fax_no')->nullable();
            $table->string('address')->nullable();            
            $table->string('state')->nullable();
            $table->string('nationality')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('email');
            $table->string('title')->nullable();

            
            $table->integer('cart_package')->nullable();
            $table->longText('cart_addons')->nullable();


            $table->softDeletes();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenant_infos');
    }
}
