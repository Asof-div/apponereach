<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->unsigned()->index()->nullable();
            $table->string('name');
            $table->string('label');
            $table->text('description')->nullable();
            $table->boolean('system')->default(false);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('operator_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('label');
            $table->text('description')->nullable();
            $table->boolean('system')->default(false);
            $table->timestamps();
        });

        Schema::create('admin_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('label');
            $table->text('description')->nullable();
            $table->boolean('system')->default(false);
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
        
        Schema::dropIfExists('roles');
        Schema::dropIfExists('operator_roles');
        Schema::dropIfExists('admin_roles');

    }
}
