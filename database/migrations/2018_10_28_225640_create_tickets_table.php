<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ticket_no')->unique();
            $table->longText('body')->nullable();
            $table->string('title')->nullable();
            $table->integer('incident_id')->nullable();
            $table->integer('tenant_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('initial_response')->nullable();
            $table->string('initial_response_unit')->nullable();
            $table->string('escalation_interval')->nullable();
            $table->string('escalation_interval_unit')->nullable();
            $table->string('expected_resolution')->nullable();
            $table->string('expected_resolution_unit')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamp('closed_date')->nullable();
            $table->boolean('isOverdue')->default(0);
            $table->string('status')->default('Unassigned'); // Open, Pending, 'Closed', 'Resolved'
            $table->string('priority')->default('Medium');  // Medium, Low, High, Urgent
            $table->string('severity')->default('Normal'); // /Emergency, Critical, Major, Minor Normal', 'Low', 'High', 'Urgent'
            $table->string('creator_type')->nullable();
            $table->integer('creator_id')->nullable();
            $table->integer('chat_room_id')->nullable();
            $table->integer('assigned_operator_id')->nullable()->unsigned()->index();
            $table->integer('assigned_admin_id')->nullable()->unsigned()->index();
            $table->integer('created_by_operator_id')->nullable()->unsigned()->index();

            $table->timestamps();

            $table->foreign('incident_id')->references('id')->on('incidents')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('assigned_operator_id')->references('id')->on('operators')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('created_by_operator_id')->references('id')->on('operators')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('assigned_admin_id')->references('id')->on('admins')->onDelete('set null')->onUpdate('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
