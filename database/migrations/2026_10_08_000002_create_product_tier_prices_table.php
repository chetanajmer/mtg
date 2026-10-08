<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTierPricesTable extends Migration
{
    public function up()
    {
        Schema::create('product_tier_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned()->index();
            $table->integer('min_qty');
            $table->integer('max_qty')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_tier_prices');
    }
}
