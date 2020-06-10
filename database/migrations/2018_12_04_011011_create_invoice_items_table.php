<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->unsigned()->index();
            $table->integer('invoice_id')->index()->unsigned();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('list_order')->default(0);
            $table->boolean('optional')->default(0);
            $table->integer('quantity')->default(0);
            $table->string('delivery_time')->nullable();
            $table->decimal('uprice', 20, 2)->default(0.00);
            $table->decimal('price', 25, 2)->default(0.00);
            $table->timestamps(); 

            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
}
