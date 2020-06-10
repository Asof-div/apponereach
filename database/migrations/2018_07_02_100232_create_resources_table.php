<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->index()->unsigned()->nullable();
            $table->string('original_name');
            $table->string('ext');
            $table->string('mime_type')->nullable();
            $table->boolean('error')->default(0);
            $table->string('filename');
            $table->string('size');
            $table->string('path');
            $table->string('owner_type')->default('User');
            $table->integer('user_id')->index()->unsigned()->nullable();
            $table->integer('admin_id')->index()->unsigned()->nullable();
            $table->integer('operator_id')->index()->unsigned()->nullable();
            $table->string('category')->nullable();
            $table->string('file_type')->nullable();
            $table->integer('module_id')->nullable();
            $table->string('module_type')->nullable();
            $table->timestamps();


            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('operator_id')->references('id')->on('operators')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resources');
    }
}
