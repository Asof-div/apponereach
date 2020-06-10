<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->nullable();
            $table->string('caller_name')->nullable();
            $table->string('caller_num')->nullable();
            $table->integer('tenant_id')->unsigned()->index();
            $table->integer('contact_id')->unsigned()->index()->nullable();
            $table->string('direction')->nullable();
            $table->string('source')->nullable();
            $table->string('did_number')->nullable();
            $table->string('callee_name')->nullable();
            $table->string('callee_num')->nullable();
            $table->text('play_media_name')->nullable();
            $table->string('play_media_type')->nullable();
            $table->string('status')->nullable();
            $table->string('duration')->nullable();
            $table->string('billsec')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('answer_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->boolean('recorded')->default(false);
            $table->text('call_recording')->nullable();
            $table->string('dest_type')->nullable();
            $table->boolean('leave_voicemail')->default(false);
            $table->jsonb('call_trace')->nullable();
            $table->decimal('call_rate')->default(0.00);
            $table->decimal('airtime')->default(0.00);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('set null')->onUpdate('cascade');

        });

        Schema::create('user_call', function (Blueprint $table) {
            $table->integer('call_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->string('call_direction')->nullable();
            $table->foreign('call_id')->references('id')->on('calls')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_call');
        Schema::dropIfExists('calls');
    }
}
