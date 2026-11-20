<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderInvoicesTable extends Migration
{
    /**
     * Invoices issued from the order workflow (first: the 50% advance payment
     * invoice after step 3). "data" is a frozen copy of everything printed on
     * the invoice, so later changes to the order never alter an issued one.
     */
    public function up()
    {
        Schema::create('order_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->index();
            $table->unsignedTinyInteger('step');
            $table->string('type', 20);
            $table->string('number', 40)->unique();
            $table->decimal('amount', 12, 2)->default(0);
            $table->json('data');
            $table->string('issued_by')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
            $table->unique(['order_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_invoices');
    }
}
