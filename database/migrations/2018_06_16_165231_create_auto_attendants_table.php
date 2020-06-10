<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutoAttendantsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('auto_attendants', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('tenant_id')->unsigned()->index();
				$table->integer('pilot_line_id')->unsigned()->index();
				$table->string('number');
				$table->string('title')->nullable();
				$table->string('period')->default('24-7');
				$table->string('start_mon')->nullable();
				$table->string('start_day')->nullable();
				$table->string('end_day')->nullable();
				$table->string('start_time')->nullable();
				$table->string('end_time')->nullable();
				$table->integer('days')->nullable();
				$table->string('custom_day')->nullable();

				$table->boolean('voicemail')->default(0);
				$table->string('greeting_type')->nullable(0);
				$table->integer('greeting')->index()->unsigned()->nullable();
				$table->mediumText('greeting_text')->nullable();
				$table->mediumText('greeting_path')->nullable();
				$table->boolean('play_moh')->default(false);
				$table->integer('moh_id')->nullable();

				$table->boolean('record')->default(false);
				$table->string('recording_period')->default('all');
				$table->string('recording_days')->default('127');
				$table->string('recording_start')->default('00:00');
				$table->string('recording_end')->default('24:00');
				$table->boolean('custom_schedule')->default(0);
				$table->string('action')->nullable();
				$table->string('value')->nullable();
				$table->longText('params')->nullable();
				$table->integer('ring_time')->nullable();
				$table->integer('module_id')->nullable();
				$table->string('module_type')->nullable();
				$table->string('destination_type')->nullable();
				$table->string('destination_label')->nullable();
				$table->string('destination_number')->nullable();
				$table->boolean('enable')->default(false);

				$table->integer('order')->default(0);

				$table->integer('call_flow_id')->nullable();

				$table->timestamps();

				$table->foreign('pilot_line_id')->references('id')->on('pilot_lines')->onDelete('cascade')->onUpdate('cascade');

				$table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');

			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('auto_attendants');
	}
}
