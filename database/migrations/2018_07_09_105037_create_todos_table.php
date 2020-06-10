<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->unsigned()->index();
            $table->string('title')->nullable();
            $table->integer('assignee_id')->index()->unsigned();
            $table->integer('assigner_id')->index()->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->string('status'); // ['open', 'canceled', 'rejected', 'deffered', 'waiting', 'completed']
            $table->boolean('isOverdue')->default(0); 
            $table->string('priority'); // ['Low', 'Normal', 'High'])->default('Normal']
            $table->string('type')->nullable(); // ['Call', 'Email', 'Meeting', 'Event', 'Post', 'Others', 'Bill', 'Opportunity']
            $table->date('start_date')->nullable();
            $table->time('start_time')->nullable();
            
            $table->date('end_date')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('completion')->default(0);
            $table->integer('completion_speed')->default(0);
            $table->integer('score')->default(0);

            $table->integer('duration')->default(0);
            $table->string('duration_unit')->nullable(); 

            $table->boolean('finished')->default(false);

            $table->boolean('isConcluded')->default(false);;
            $table->dateTime('concluded_at')->nullable();
            $table->boolean('cancel')->default(false);
            $table->boolean('pause_request_sent')->default(false);
            $table->boolean('paused')->default(false);
            $table->dateTime('paused_at')->nullable();
            $table->string('pause_reason')->nullable();
            $table->string('rejection') ->nullable(); //'Pending', 'Approved'
            $table->string('rejection_reason')->nullable();
            $table->boolean('needs_review')->default(false);
            $table->boolean('repeat_task')->default(false);
            $table->string('repeat_interval')->nullable();
            $table->json('repeat_interval_opts')->nullable();
            $table->string('repeat_end_type')->nullable();
            $table->dateTime('repeat_end_date')->nullable();
            $table->dateTime('last_repeated_at')->nullable();
            $table->dateTime('last_reminder_sent_at')->nullable();

            $table->timestamps();

            $table->text('report')->nullable();
            $table->longText('back_log')->nullable();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('assignee_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('assigner_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->integer('deleted_by')->unsigned()->index()->nullable();
            
            $table->softDeletes();



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }


}
