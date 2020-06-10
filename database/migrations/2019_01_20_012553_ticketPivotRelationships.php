<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TicketPivotRelationships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_resources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id')->unsigned()->index();
            $table->integer('resource_id')->unsigned()->index();
            $table->integer('comment_id')->unsigned()->index()->nullable();
            $table->boolean('allow_tenant')->default(false);
            $table->timestamps();

            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('set null')->onUpdate('cascade');
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
        Schema::dropIfExists('ticket_resources');
    }
}
