<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->decimal('sub_total')->default(0);
            $table->decimal('shipping')->default(0);
            $table->decimal('tax')->default(0);
            $table->decimal('discount')->default(0);
            $table->decimal('total_price')->default(0);
            $table->text('note')->nullable();
            $table->enum('status',['New', 'Declined'])->default('New');
            $table->text('decline_note')->nullable();
            $table->integer('created_by');
            $table->string('address',255);
            $table->string('address2',255);
            $table->string('city',100);
            $table->string('state',100);
            $table->string('zip',100);
            $table->string('country',100);
            $table->string('phone',100);
            $table->decimal('total_paid')->default(0);

            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade')->onUpdate("cascade");
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
        Schema::dropIfExists('quotes');
    }
}
