<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupCallsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('group_calls', function (Blueprint $table) {
				$table->increments('id');
				$table->string('name');
				$table->integer('tenant_id')->unsigned()->index();
				$table->string('code');
				$table->string('context')->nullable();
				$table->string('call_strategy')->nullable();
				$table->string('number');
				$table->integer('sound_id')->nullable()->unsigned()->index();
				$table->text('moh')->nullable();
				$table->json('members')->nullable();
				$table->integer('call_flow_id')->unsigned()->nullable();
				$table->string('fallback_action')->nullable();
				$table->string('fallback_type')->nullable();
				$table->string('fallback_label')->nullable();
				$table->timestamps();

				$table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
				$table->foreign('sound_id')->references('id')->on('play_media')->onDelete('set null')->onUpdate('cascade');
				$table->foreign('call_flow_id')->references('id')->on('call_flows')->onDelete('cascade')->onUpdate('cascade');
			});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('group_calls');
	}
}
