<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->index()->unsigned();
            $table->integer('package_id')->index()->unsigned()->nullable();
            $table->string('billing_method')->default('prepay'); //postpay, prepay
            $table->string('duration')->nullable();
            $table->string('cycle')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->string('amount')->nullable();
            $table->string('currency')->default('&#x20A6;');
            $table->jsonb('addons')->nullable();
            $table->jsonb('extra_msisdn')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->integer('manager_id')->nullable()->unsigned()->index();
            $table->string('payment_status')->default('unpaid');
            $table->string('invoice_pdf')->nullable();
            $table->string('total')->nullable();
            $table->integer('order_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
