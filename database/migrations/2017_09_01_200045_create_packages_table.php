<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('price');
            $table->string('currency')->default('&#x20A6;');
            $table->text('description');
            $table->string('msisdn_limit')->nullable();
            $table->string('user_limit')->nullable();
            $table->string('extension_limit')->nullable();
            $table->string('addon_binary')->default(0);
            $table->text('note')->nullable();
            $table->string('annually')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('packages');
    }
}
