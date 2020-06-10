<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->index()->unsigned();
            $table->integer('subscription_id')->index()->unsigned();
            $table->integer('order_id')->index()->unsigned();
            $table->string('transaction_no')->unique();
            $table->string('transaction_method');
            $table->timestamp('ordered_date')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->nullable();
            $table->string('description')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('currency')->default('NGN');
            $table->string('payment_method')->nullable();
            $table->string('payment_channel')->nullable();
            $table->string('discount_type')->nullable();
            $table->string('discount_value')->nullable();
            $table->string('status')->default('progress');
            $table->string('amount');
            $table->string('generated_by')->default('user');
            $table->string('generator_name')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->string('pdf')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
