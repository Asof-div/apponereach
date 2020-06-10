<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('group_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->unsigned()->index();
            $table->integer('group_id')->unsigned()->index();
            $table->integer('member_id')->nullable();
            $table->string('member_type')->nullable();
            $table->string('label')->nullable();
            $table->string('type')->nullable();
            $table->string('number')->nullable();
            $table->integer('ordered_list')->default(0);
            $table->timestamps();
            $table->foreign('group_id')->references('id')->on('group_calls')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('group_members');
    }
}
