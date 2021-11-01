<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrizeOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prize_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prize_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pos_id');
            $table->integer('value')->nullable();
            $table->integer('saldo')->nullable();
            $table->date('order_date')->nullable();
            $table->date('release_date')->nullable();
            $table->integer('days_from_order')->nullable();
            $table->integer('days_late')->nullable();
            $table->string('tax_declaration')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->string('full_name')->nullable()->comment('Shipping information');
            $table->string('phone')->nullable()->comment('Shipping information');
            $table->string('email')->nullable()->comment('Shipping information');
            $table->string('address')->nullable()->comment('Shipping information');
            $table->string('postal_code')->nullable()->comment('Shipping information');
            $table->string('city')->nullable()->comment('Shipping information');
            $table->timestamps();

            $table->foreign('prize_id')->references('id')->on('prizes');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('pos_id')->references('id')->on('pos');
            $table->foreign('status_id')->references('id')->on('order_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prize_orders');
    }
}
