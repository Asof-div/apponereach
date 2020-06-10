<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('users', function (Blueprint $table) {
				$table->increments('id');
				$table->string('firstname');
				$table->string('lastname');
				$table->string('middlename')->nullable();
				$table->string('email');
				$table->integer('tenant_id')->unsigned()->nullable();
				$table->boolean('manager')->default(false);
				$table->string('password');
				$table->decimal('quota', 10, 2)->default(0.00);
				$table->decimal('used_credit', 22, 2)->default(0.00);//used credits
				$table->string('credit_limit')->nullable();// shaped
				$table->string('primary_did')->nullable();
				$table->string('secondary_did')->nullable();
				$table->json('settings')->nullable();
				$table->boolean('active')->default(false);
				$table->boolean('can_be_impersonated')->default(false);
				$table->timestamp('last_login_at')->nullable();
				$table->boolean('external')->default(false);
				$table->boolean('international')->default(false);
				;
				$table->string('voicemail_pin')->nullable();
				$table->string('fallback_action')->nullable();
				$table->string('fallback_type')->nullable();
				$table->string('fallback_label')->nullable();
				$table->rememberToken();
				$table->timestamps();
				$table->softDeletes();
				$table->integer('deleted_by')->nullable();

				$table->foreign('deleted_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
				$table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('users');
	}
}
