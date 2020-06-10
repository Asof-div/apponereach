<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('country_id')->index()->unsigned()->nullable();
            $table->string('domain')->nullable();
            $table->string('tenant_no')->unique();
            $table->string('status')->default('idle'); //idle, registration, activated, pending, processing, suspended, leave
            $table->string('stage')->nullable();
            $table->boolean('expired')->default(1);
            $table->string('code')->nullable();
            $table->string('billing_method')->default('prepay'); //prepay, postpay
            $table->string('billing_cycle')->default('monthly'); //monthly, trimontly ,yearly
            $table->integer('last_subscription')->nullable();
            $table->integer('current_subscription')->nullable();
            $table->integer('package_id')->unsigned()->index()->nullable();
            $table->timestamp('expiration_date')->nullable();
            $table->integer('grace')->default(3);
            $table->string('support')->nullable();
            $table->boolean('activated')->default(0);
            $table->boolean('auto_rebill')->default(0);
            $table->decimal('credits', 10, 2)->default(0.00);
            $table->decimal('used_credits', 22, 2)->default(0.00);
            $table->json('settings')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('set null');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenants');
    }
}
