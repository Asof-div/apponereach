<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numbers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->unsigned()->index();
            $table->string('name')->nullable();
            $table->string('number')->nullable();
            $table->string('code')->nullable();
            $table->string('context')->nullable();
            $table->string('cug')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('scode')->nullable();
            $table->integer('scode_flow_id')->unsigned()->index()->nullable();
            $table->boolean('status')->default(false);
            $table->string('status_msg')->nullable();
            $table->boolean('call_recording')->default(0);
            $table->boolean('voicemail')->default(0);
            $table->text('follow_me')->nullable();
            $table->integer('call_flow_id')->unsigned()->index()->nullable();
            $table->boolean('slot')->default(0);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('call_flow_id')->references('id')->on('call_flows')->onDelete('set null')->onUpdate('cascade');

            $table->foreign('scode_flow_id')->references('id')->on('call_flows')->onDelete('set null')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('numbers');
    }
}
