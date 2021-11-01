<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('number_pos_main')->comment('Numer POS z importu klienta');
            $table->string('number_pos')->comment('Numer POS z importu klienta');
            $table->enum('type', ['firma', 'siec']);
            $table->unsignedBigInteger('period_id');
            $table->integer('turnover')->default(0);
            $table->integer('threshold_basic')->default(0);
            $table->integer('threshold_silver')->default(0);
            $table->integer('threshold_gold')->default(0);
            $table->integer('basic_points')->default(0);
            $table->integer('silver_points')->default(0);
            $table->integer('gold_points')->default(0);
            $table->integer('lacking_points_basic')->default(0);
            $table->integer('lacking_points_silver')->default(0);
            $table->integer('lacking_points_gold')->default(0);
            $table->integer('active_points')->nullable();
            $table->integer('inactive_points')->nullable();
            $table->enum('status',['available', 'locked']);
            $table->timestamps();

            $table->foreign('period_id')->references('id')->on('periods');
            $table->foreign('number_pos')->references('number_pos')->on('pos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('results');
    }
}
