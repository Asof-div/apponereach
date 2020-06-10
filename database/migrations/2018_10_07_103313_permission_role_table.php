<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned()->index();
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('permission_id')->references('id')->on('permissions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
            
        });

        Schema::create('operator_permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned()->index();
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('permission_id')->references('id')->on('operator_permissions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('operator_roles')->onUpdate('cascade')->onDelete('cascade');
            
        });

        Schema::create('admin_permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned()->index();
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('permission_id')->references('id')->on('admin_permissions')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('operator_permission_role');
        Schema::dropIfExists('admin_permission_role');
    }
}
