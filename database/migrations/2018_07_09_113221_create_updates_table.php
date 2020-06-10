<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('updates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->unsigned()->index();
            $table->integer('user_id')->index()->unsigned()->nullable();
            $table->integer('account_id')->index()->unsigned()->nullable();
            $table->integer('todo_id')->index()->unsigned()->nullable();
            $table->integer('opportunity_id')->index()->unsigned()->nullable();
            $table->integer('parent')->index()->unsigned()->nullable();
            $table->integer('module_id')->index()->unsigned();
            $table->string('module_type')->nullable();
            $table->string('type')->nullable();
            $table->string('subject')->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('opportunity_id')->references('id')->on('opportunities')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('todo_id')->references('id')->on('todos')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('parent')->references('id')->on('updates')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('updates');
    }
}
