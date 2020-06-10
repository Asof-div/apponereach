<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePilotNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilot_numbers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->nullable();
            $table->integer('type_id')->nullable();
            $table->string('number')->unique();
            $table->string('label')->nullable();
            $table->integer('batch')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('provisioning')->default('Not Provisioned');
            $table->boolean('available')->default(1);
            $table->boolean('deactivated')->default(0);
            $table->boolean('ported')->default(0);
            $table->string('source')->default("Operator");
            $table->integer('order_id')->nullable();
            $table->boolean('purchased')->default(0);
            $table->timestamp('release_time')->nullable();
            $table->string('operator_type')->nullable();
            $table->integer('operator_id')->nullable();
            $table->timestamp('rental_date')->nullable();
            $table->timestamp('rental_expiration_date')->nullable();
            $table->string('status')->default('unallocated'); //unallocated, cart, allocated

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
        Schema::dropIfExists('pilot_numbers');
    }
}
