<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallFlowsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('call_flows', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('tenant_id')->unsigned()->index();
				$table->string('context')->default('default');// context
				$table->string('code')->nullable();// context
				$table->string('direction')->nullable();//intercom, outbound, inbound, test - extension
				$table->string('title')->nullable();
				$table->string('dial_string')->nullable();// 009902 - designation number
				$table->string('conditions')->default('destination');// destination, all, custom, date ..
				$table->integer('greeting')->nullable();
				$table->string('greeting_type')->nullable();
				$table->mediumText('greeting_path')->nullable();

				$table->boolean('play_moh')->default(false);
				$table->mediumText('moh_path')->nullable();
				$table->boolean('enable')->default(false);

				$table->string('wday')->nullable();
				$table->string('mon')->nullable();
				$table->string('start_day')->nullable();
				$table->string('end_day')->nullable();
				$table->string('start_time')->default('00:00');
				$table->string('end_time')->default('24:00');
				$table->string('custom_day')->nullable();
				$table->integer('priority')->nullable();
				$table->boolean('active')->default(false);
				$table->boolean('voicemail')->default(false);
				$table->boolean('save_voicemail')->default(false);
				$table->boolean('send_to_voicemail')->default(false);
				$table->string('voicemail_number')->nullable();
				$table->string('voicemail_prompt_type')->default(false);
				$table->longText('voicemail_prompt')->nullable();
				$table->boolean('record_call')->default(false);
				$table->string('recording_period')->default('all');
				$table->string('recording_wday')->nullable();
				$table->string('recording_start')->default('00:00');
				$table->string('recording_end')->default('24:00');
				$table->string('dest_type')->nullable();
				$table->integer('dest_id')->nullable();
				$table->longText('dest_params')->nullable();

				$table->boolean('fallback')->default(false);
				$table->longText('fallback_params')->nullable();
				$table->string('fallback_type')->nullable();
				$table->string('fallback_label')->nullable();

				$table->timestamps();

				$table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');

			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('call_flows');
	}
}
