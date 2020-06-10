<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtualReceptionistMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_receptionist_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->index()->unsigned();
            $table->string('context')->nullable();
            $table->integer('virtual_receptionist_id')->index()->unsigned();
            $table->string('key_press');
            $table->string('menu_type');
            $table->string('menu_label')->nullable();
            $table->string('menu_action_label')->nullable();
            $table->longText('params')->nullable();
            $table->integer('module_id')->nullable();
            $table->string('module_type')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('virtual_receptionist_id')->references('id')->on('virtual_receptionists')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('virtual_receptionist_menus');
    }
}
