<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConferencesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('conferences', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('tenant_id')->index()->unsigned()->nullable();
				$table->string('code')->nullable();
				$table->string('context')->nullable();
				$table->string('type')->default('Local');
				$table->string('mode')->default('Audio');
				$table->string('name')->nullable();
				$table->string('number');
				$table->json('members')->nullable();
				$table->boolean('auto_record')->default(false);
				$table->boolean('wait_admin')->default(false);
				$table->boolean('lock')->default(false);
				$table->boolean('announce')->default(false);
				$table->boolean('record')->default(false);
				$table->string('admin_pin')->nullable();
				$table->string('guest_pin')->nullable();
				$table->boolean('enable_audio')->default(true);
				$table->boolean('enable_video')->default(false);
				$table->integer('welcome_sound')->index()->unsigned()->nullable();
				$table->timestamp('start_date')->nullable();
				$table->timestamp('end_date')->nullable();
				$table->string('expiration')->nullable();
				$table->integer('call_flow_id')->unsigned()->nullable();
				$table->boolean('chat')->default(false);
				$table->boolean('attendants_list')->default(false);
				$table->timestamps();
				$table->softDeletes();

				$table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
				$table->foreign('welcome_sound')->references('id')->on('play_media')->onDelete('set null')->onUpdate('cascade');
				$table->foreign('call_flow_id')->references('id')->on('call_flows')->onDelete('cascade')->onUpdate('cascade');
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('conferences');
	}
}
