<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('label')->nullable();
            $table->string('initial_response')->nullable();
            $table->string('initial_response_unit')->nullable();
            $table->string('escalation_interval')->nullable();
            $table->string('escalation_interval_unit')->nullable();
            $table->string('expected_resolution')->nullable();
            $table->string('expected_resolution_unit')->nullable();
            $table->string('priority')->default('Medium'); // 'Medium', 'Low', 'High', 'Urgent'
            $table->string('severity')->default('Normal'); // /Emergency, Critical, Major, Minor Normal', 'Low', 'High', 'Urgent'
            $table->boolean('apply_to_user')->nullable();
            $table->boolean('escalate_to_admin')->default();
            $table->integer('operator_id')->index()->unsigned()->nullable();
            $table->integer('admin_id')->index()->unsigned()->nullable();
            $table->timestamps();
        
            $table->foreign('operator_id')->references('id')->on('operators')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incidents');
    }
}
