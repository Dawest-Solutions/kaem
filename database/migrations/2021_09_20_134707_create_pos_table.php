<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreignId('ph_id')->nullable();
            $table->string('number_pos_main');
            $table->string('number_pos')->unique();
            $table->enum('type', ['firma', 'siec']);
            $table->string('company_name');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('ph_id')->references('id')->on('ph');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pos');
    }
}
