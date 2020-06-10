<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_routes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->index()->unsigned();
            $table->string('title')->nullable();
            $table->boolean('record')->default(0);
            $table->boolean('voicemail')->default(0);
            $table->string('greeting_type')->default(0);
            $table->integer('greeting')->index()->unsigned()->nullable();
            $table->mediumText('greeting_param')->nullable();
            $table->boolean('play_moh')->default(false);
            $table->integer('moh_id')->nullable();
            $table->boolean('auto_recording')->default(0);
            $table->string('recording_key')->nullable();
            $table->string('record_start_time')->nullable();
            $table->string('record_end_time')->nullable();
            $table->string('record_days_bits')->nullable();
            $table->boolean('custom_schedule')->default(0);
            $table->string('action')->nullable();
            $table->string('value')->nullable();
            $table->longText('params')->nullable();
            $table->integer('ring_time')->nullable();
            $table->integer('module_id')->nullable();
            $table->string('module_type')->nullable();
            $table->string('dest_type')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('greeting')->references('id')->on('play_media')->onDelete('set null')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('call_routes');
    }
}
