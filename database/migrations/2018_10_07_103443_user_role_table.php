<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_role', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('operator_role', function (Blueprint $table) {
            $table->integer('operator_id')->unsigned()->index();
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('operator_id')->references('id')->on('operators')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('operator_roles')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('admin_role', function (Blueprint $table) {
            $table->integer('admin_id')->unsigned()->index();
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('admin_id')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('admin_roles')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_role');
        Schema::dropIfExists('operator_role');
        Schema::dropIfExists('admin_role');
    }
}
