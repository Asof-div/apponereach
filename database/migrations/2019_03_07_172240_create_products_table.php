<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->nullable();
            $table->integer('product_category_id')->nullable();
            $table->string('name');
            $table->string('label')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            
            $table->foreign('product_category_id')->references('id')->on('product_categories')->onUpdate('cascade')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
