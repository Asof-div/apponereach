<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('currency_id')->index()->unsigned()->nullable();
            $table->string('iso');
            $table->string('name');
            $table->string('nicename');
            $table->string('iso3');
            $table->string('numcode');
            $table->string('phonecode');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('SET NULL')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
