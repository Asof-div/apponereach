<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->index()->unsigned();
            $table->integer('transaction_id')->nullable();
            $table->integer('subscription_id')->index()->unsigned();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->nullable();
            $table->decimal('amount',20,2)->default(0.00);
            $table->string('discount_type')->nullable();
            $table->decimal('discount',20,2)->default(0.00);
            $table->decimal('charged', 20, 2)->default(0.00);
            $table->string('currency')->default('NGN');
            $table->string('pdf')->nullable();

            $table->string('status')->nullable();
            $table->boolean('payment_status')->default(false);
            $table->timestamp('ordered_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade')->onUpdate('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
