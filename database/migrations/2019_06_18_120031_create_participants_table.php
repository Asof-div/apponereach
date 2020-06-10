<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('participants', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('meeting_id')->index()->unsigned();
				$table->string('name')->nullable();
				$table->string('email')->nullable();
				$table->string('phone')->nullable();
				$table->boolean('auto_dial')->default(false);
				$table->boolean('chairperson')->default(false);
				$table->boolean('password')->default(false);
				$table->integer('user_id')->index()->unsigned()->nullable();
				$table->timestamps();
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('participants');
	}
}
