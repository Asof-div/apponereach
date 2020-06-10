<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operator_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('operator_id')->unsigned()->index();
            $table->integer('incident_id')->unsigned()->index();
            $table->integer('escalation_level')->default(1);
            $table->foreign('operator_id')->references('id')->on('operators')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('incident_id')->references('id')->on('incidents')->onDelete('cascade')->onUpdate('cascade');

        });
    
        Schema::create('admin_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->unsigned()->index();
            $table->integer('incident_id')->unsigned()->index();
            $table->integer('escalation_level')->default(1);
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('incident_id')->references('id')->on('incidents')->onDelete('cascade')->onUpdate('cascade');

        });


        Schema::create('ticket_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('ticket_id')->unsigned()->index();
            $table->integer('escalation_level')->default(1);
            $table->boolean('active')->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade')->onUpdate('cascade');

        });

        Schema::create('ticket_operator', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('operator_id')->unsigned()->index();
            $table->integer('ticket_id')->unsigned()->index();
            $table->integer('escalation_level')->default(1);
            $table->boolean('active')->default(0);
            $table->foreign('operator_id')->references('id')->on('operators')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade')->onUpdate('cascade');

        });
    
        Schema::create('ticket_admin', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->unsigned()->index();
            $table->integer('ticket_id')->unsigned()->index();
            $table->integer('escalation_level')->default(1);
            $table->boolean('active')->default(0);
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade')->onUpdate('cascade');

        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operator_teams');
        Schema::dropIfExists('admin_teams');
        Schema::dropIfExists('ticket_user');
        Schema::dropIfExists('ticket_operator');
        Schema::dropIfExists('ticket_admin');
    }
}
