<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extensions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->index()->unsigned();
            $table->string('name');
            $table->integer('user_id')->index()->unsigned()->nullable();
            $table->integer('call_flow_id')->index()->unsigned()->nullable();
            $table->string('type')->default('sip_profile');
            $table->string('number');
            $table->string('context')->nullable();
            $table->string('exten_reg')->nullable();
            $table->string('password')->nullable();
            $table->boolean('follow_me')->default(false);
            $table->boolean('moh')->default(false);
            $table->boolean('whispering')->default(false);
            $table->boolean('full_monitoring')->default(false);
            $table->boolean('call_recording')->default(false);
            $table->boolean('voicemail')->default(false);
            $table->boolean('annouce_caller')->default(false);
            $table->string('eavesdropping_pin')->nullable();
            $table->string('whispering_pin')->nullable();
            $table->string('full_monitoring_pin')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('call_flow_id')->references('id')->on('call_flows')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extensions');
    }
}
