<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('quote_id');
            $table->unsignedBigInteger('product_id');
            $table->bigInteger('quantity');
            $table->decimal('price');
            $table->decimal('discount');
            $table->timestamps();

            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('cascade')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quote_products');
    }
}
