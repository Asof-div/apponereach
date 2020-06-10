<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('play_media', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->index()->unsigned();
            $table->string('title');
            $table->string('code')->nullable();
            $table->string('voice_code')->nullable();
            $table->string('application')->default('file');
            $table->string('original_name')->nullable();
            $table->string('ext')->nullable();
            $table->string('dial_code')->nullable();
            $table->string('source')->nullable();
            $table->string('category')->nullable();
            $table->string('mime_type')->nullable();
            $table->boolean('error')->default(0);
            $table->string('filename')->nullable();
            $table->string('size')->nullable();
            $table->string('path')->nullable();
            $table->text('content')->nullable();
            $table->boolean('exist')->default(0);
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('play_media');
    }
}
