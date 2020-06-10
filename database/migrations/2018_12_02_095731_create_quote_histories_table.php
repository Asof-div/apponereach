<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quote_id')->unsigned()->index();
            $table->integer('resource_id')->unsigned()->index();
            $table->integer('revision')->default(1);
            $table->text('update')->nullable();
            $table->timestamps();

            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('cascade')->onUpdate('cascade');


        });

        Schema::create('quote_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quote_id')->unsigned()->index();
            $table->integer('contact_id')->unsigned()->index();
            $table->integer('revision')->default(1);
            $table->text('update')->nullable();
            $table->timestamps();

            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('cascade')->onUpdate('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quote_histories');
        Schema::dropIfExists('quote_contacts');
    }
}
