<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->unsigned()->index();
            $table->string('name');
            $table->integer('account_category_id')->unsigned()->index()->nullable();
            $table->string('id_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->integer('state_id')->nullable()->index()->unsigned();
            $table->integer('country_id')->index()->unsigned()->nullable();
            $table->string('postcode')->nullable();
            $table->string('website')->nullable();
            $table->integer('account_source_id')->unsigned()->index()->nullable();
            $table->text('private_note')->nullable();
            $table->text('public_note')->nullable();
            $table->integer('payment_term_id')->nullable()->index()->unsigned();
            $table->integer('currency_id')->unsigned()->index()->nullable();
            $table->integer('industry_id')->index()->unsigned()->nullable();
            $table->integer('account_manager')->unsigned()->index()->nullable();
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->softDeletes();
            $table->integer('deleted_by')->unsigned()->index()->nullable();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('account_manager')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('account_category_id')->references('id')->on('account_categories')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->foreign('account_source_id')->references('id')->on('account_sources')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->foreign('payment_term_id')->references('id')->on('payment_terms')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('SET NULL');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('SET NULL');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->foreign('industry_id')->references('id')->on('industries')->onDelete('SET NULL');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('SET NULL');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
