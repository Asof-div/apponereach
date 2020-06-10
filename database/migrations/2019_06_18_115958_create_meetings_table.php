<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('meetings', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('tenant_id')->index()->unsigned()->nullable();
				$table->string('code')->nullable();
				$table->string('context')->nullable();
				$table->string('subject');
				$table->string('conference_id')->index()->unsigned()->nullable();
				$table->string('meeting_room_id')->nullable();
				$table->string('description')->nullable();
				$table->date('start_date')->nullable();
				$table->date('end_date')->nullable();
				$table->string('start_time')->nullable();
				$table->string('end_time')->nullable();
				$table->string('duration')->nullable();
				$table->integer('created_by')->nullable();
				$table->string('creator_id')->nullable();
				$table->json('email_invites')->nullable();
				$table->timestamps();
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('meetings');
	}
}
