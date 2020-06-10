<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpportunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->unsigned()->index();
            $table->string('opportunity_no')->unique();
            $table->string('title');
            $table->string('category')->nullable();
            $table->boolean('isRecurrent')->default(0);
            $table->integer('account_id')->index()->unsigned();
            $table->string('stage'); // ['New Lead', 'Lost', 'Won', 'Qualified', 'Waiting', 'Meeting', 'Waiting For PO',]
            $table->string('attention'); // ['Cold', 'Warm', 'Hot']
            $table->integer('probability')->default(0);
            $table->integer('currency_id')->index()->unsigned()->nullable();
            $table->decimal('worth', 22,2)->default(0.00);
            $table->string('source')->nullable();
            $table->boolean('status')->default(0);
            $table->integer('competitor_id')->index()->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->integer('manager_id')->index()->unsigned()->nullable();
            $table->integer('created_by')->index()->unsigned()->nullable();
            $table->timestamp('close_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('deleted_by')->unsigned()->index()->nullable();

            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('manager_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('competitor_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('set null');

        });

        Schema::create('opportunity_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('opportunity_id')->unsigned()->index();
            $table->integer('contact_id')->unsigned()->index();

            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('opportunity_id')->references('id')->on('opportunities')->onDelete('cascade')->onUpdate('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opportunity_contacts');
        Schema::dropIfExists('opportunities');
    }
}
