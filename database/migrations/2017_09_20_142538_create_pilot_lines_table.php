<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilotLinesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('pilot_lines', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('tenant_id')->index()->unsigned();
				$table->string('number')->unique();
				$table->string('label')->nullable();
				$table->integer('type_id')->nullable();
				$table->string('dedication')->nullable();
				$table->boolean('in_use')->default(0);
				$table->boolean('call_forwarding')->default(0);
				$table->string('forward_to')->nullable();
				$table->boolean('status')->default(0);
				$table->boolean('configured')->default(0);
				$table->string('greeting_type')->default(0);
				$table->integer('greeting')->index()->unsigned()->nullable();
				$table->mediumText('greeting_text')->nullable();
				$table->mediumText('greeting_path')->nullable();

				$table->boolean('play_moh')->default(false);
				$table->integer('moh_id')->index()->unsigned()->nullable();

				$table->boolean('record')->default(0);
				$table->boolean('voicemail')->default(0);
				$table->string('voicemail_email')->nullable();
				$table->boolean('send_voicemail_to_email')->default(0);
				$table->boolean('save_voicemail')->default(1);
				$table->string('voicemail_prompt_type')->default(0);
				$table->integer('voicemail_prompt')->nullable();

				$table->string('recording_period')->default('all');
				$table->string('recording_days')->default('127');
				$table->string('recording_start')->default('00:00');
				$table->string('recording_end')->default('24:00');
				$table->string('destination_type')->nullable();
				$table->string('destination_label')->nullable();
				$table->string('destination_number')->nullable();
				$table->string('caller_id_name')->nullable();
				$table->boolean('strict_caller_id')->default(1);
				$table->boolean('show_inbound_caller_id')->default(0);
				$table->integer('call_flow_id')->nullable()->index()->unsigned();
				$table->boolean('actived_for_service')->default(0);
				$table->longText('params')->nullable();
				$table->string('module_type')->nullable();
				$table->integer('module_id')->nullable();
				$table->timestamp('rental_date')->nullable();
				$table->timestamp('rental_expiration_date')->nullable();
				$table->timestamps();

				$table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');

				$table->foreign('call_flow_id')->references('id')->on('call_flows')->onDelete('set null')->onUpdate('cascade');
				$table->softDeletes();

			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('pilot_lines');
	}
}
