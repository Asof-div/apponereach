<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvisioningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provisionings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mac_addr')->unique();
            $table->string('exten');
            $table->string('password')->nullable();
            $table->string('server');
            $table->string('phone_type');
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
        Schema::dropIfExists('provisionings');
    }
}
