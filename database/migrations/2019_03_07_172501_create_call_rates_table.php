<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallRatesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('call_rates', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('country_id')->unsigned()->index()->nullable();
				$table->string('phonecode')->nullable();
				$table->string('rate');
				$table->boolean('default')->default(false);
				$table->timestamps();

				$table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('call_rates');
	}
}
