<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->nullable();
            $table->string('action')->nullable();
            $table->string('subject_type')->nullable();
            $table->integer('subject_id')->nullable();
            $table->text('before')->nullable();
            $table->text('after')->nullable();
            $table->text('note')->nullable();
            $table->string('user_type')->nullable();
            $table->integer('admin_id')->nullable()->index()->unsigned();
            $table->integer('operator_id')->nullable()->index()->unsigned();
            $table->integer('user_id')->nullable()->index()->unsigned();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('operator_id')->references('id')->on('operators')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_logs');
    }
}
