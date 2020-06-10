<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->unsigned()->index();
            $table->string('invoice_no')->nullable();
            $table->string('po_no')->nullable();
            $table->integer('opportunity_id')->index()->unsigned()->nullable();
            $table->integer('quote_id')->index()->unsigned()->nullable();
            $table->integer('account_id')->index()->unsigned();
            $table->string('title')->nullable();
            $table->boolean('validated')->default(0);
            $table->integer('status')->default(0);
            $table->integer('currency_id');
            $table->string('payment_terms')->nullable();
            $table->longText('terms')->nullable();
            $table->text('public_note')->nullable();
            $table->text('private_note')->nullable();
            $table->text('footer')->nullable();
            $table->decimal('deposit', 22, 2)->default(0.00);

            $table->decimal('sub_total',25,2)->nullable();

            $table->string('vat_type')->default('percentage');
            $table->string('discount_type')->default('percentage');
            
            $table->decimal('vat_rate', 16, 2)->default(5.00);
            $table->decimal('discount_rate', 16, 2)->default(0.00);
            
            $table->decimal('vat', 16, 2)->default(0.00);
            $table->decimal('discount', 16, 2)->default(0.00);
            $table->string('grand_total')->default('0.00');
            $table->string('balance_due')->default('0.00');

            $table->date('invoice_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->integer('validated_by')->index()->unsigned()->nullable();
            $table->integer('user_id')->index()->unsigned()->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('deleted_by')->unsigned()->index()->nullable();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('validated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('opportunity_id')->references('id')->on('opportunities')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
