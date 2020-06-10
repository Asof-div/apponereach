<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chat_room_id')->unsigned()->index();
            $table->string('message');
            $table->integer('sender_id')->nullable();
            $table->string('sender_type')->nullable();
            $table->timestamps();
            $table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');
        });

        Schema::create('user_chat_room', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chat_room_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');

        });

        Schema::create('operator_chat_room', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chat_room_id')->unsigned()->index();
            $table->integer('operator_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('operator_id')->references('id')->on('operators')->onDelete('cascade');
            $table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');

        });

        Schema::create('admin_chat_room', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chat_room_id')->unsigned()->index();
            $table->integer('admin_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');

        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_conversations');
        Schema::dropIfExists('user_chat_room');
        Schema::dropIfExists('operator_chat_room');
        Schema::dropIfExists('admin_chat_room');
    }
}
