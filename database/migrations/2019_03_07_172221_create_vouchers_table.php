<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->unsigned()->index()->nullable();
            $table->integer('order_id')->unsigned()->index()->nullable();
            $table->string('voucher_type');
            $table->string('voucher_code');
            $table->decimal('value', 20, 2);
            $table->decimal('price', 20, 2);
            $table->boolean('active')->default(false);
            $table->boolean('used')->default(false);

            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
