<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivateMeetingsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('private_meetings', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('tenant_id')->index()->unsigned()->nullable();
				$table->string('code')->nullable();
				$table->string('context')->nullable();
				$table->string('subject');
				$table->string('number')->nullable();
				$table->string('conference_id')->index()->unsigned();
				$table->date('start_date')->nullable();
				$table->date('end_date')->nullable();
				$table->string('start_time')->nullable();
				$table->string('end_time')->nullable();
				$table->integer('duration')->nullable();
				$table->timestamps();
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('private_meetings');
	}
}
