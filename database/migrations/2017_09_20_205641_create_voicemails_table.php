<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoicemailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voicemails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->index()->unsigned();
            $table->string('cdr_uuid')->nullable();
            $table->string('caller_id_name')->nullable();
            $table->string('caller_id_num')->nullable();
            $table->timestamp('called_time')->nullable();
            $table->string('destination_type')->nullable();
            $table->string('destination_id')->nullable();
            $table->string('number_type')->nullable();
            $table->string('number')->nullable();
            $table->string('filename')->nullable();
            $table->boolean('send_voicemail_to_mail')->default(0);
            $table->string('path')->nullable();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('voicemails');
    }
}
