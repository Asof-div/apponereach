<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVirtualReceptionistsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('virtual_receptionists', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('tenant_id')->index()->unsigned();
				$table->string('context')->nullable();
				$table->string('name');
				$table->string('number')->nullable();
				$table->string('ivr_type')->default('tts');
				$table->mediumText('ivr_msg')->nullable();
				$table->mediumText('ivr_path')->nullable();
				$table->integer('play_media_id')->index()->unsigned()->nullable();
				$table->integer('key_length')->default(3);
				$table->longText('menu_params')->nullable();
				$table->integer('call_flow_id')->unsigned()->nullable();
				$table->timestamps();

				$table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
				$table->foreign('play_media_id')->references('id')->on('play_media')->onDelete('set null')->onUpdate('cascade');

			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('virtual_receptionists');
	}
}
