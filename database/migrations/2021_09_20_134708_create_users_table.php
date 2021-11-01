<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_id')->nullable()->constrained('pos');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('pesel')->nullable();
            $table->string('phone')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('register_code')->nullable()->unique();
            $table->boolean('previous_system_user')->default(false);
            $table->rememberToken();
            $table->string('street')->nullable()->comment('PIT declaration 11');
            $table->string('building_number')->nullable()->comment('PIT declaration 11');
            $table->string('apartment_number')->nullable()->comment('PIT declaration 11');
            $table->string('postal_code')->nullable()->comment('PIT declaration 11');
            $table->string('city')->nullable()->comment('PIT declaration 11');
            $table->string('borough')->nullable()->comment('PIT declaration 11');
            $table->string('district')->nullable()->comment('PIT declaration 11');
            $table->string('voivodeship')->nullable()->comment('PIT declaration 11');
            $table->string('tax_office')->nullable()->comment('PIT declaration 11');
            $table->enum('tax_declaration', ['company','pit','company representative'])->nullable()->comment('PIT declaration 11');
            $table->text('agreements')->nullable();
            $table->timestamp('last_visit')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
