<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sub_set')->nullable();
            $table->integer('ticket_id')->unsigned()->index()->nullable();
            $table->integer('todo_id')->unsigned()->index()->nullable();
            $table->integer('quote_id')->unsigned()->index()->nullable();
            $table->integer('invoice_id')->unsigned()->index()->nullable();
            $table->integer('commentable_id')->nullable();
            $table->string('commentable_type')->nullable();
            $table->boolean('isInternal')->default(0);
            $table->text('comment')->nullable();
            $table->integer('resource_id')->unsigned()->index()->nullable();
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('tickets')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('todo_id')->references('id')->on('todos')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('quote_id')->references('id')->on('quotes')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('resource_id')->references('id')->on('resources')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
