<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAccountProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_account_profile', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_account_base_id')->nullable(false);
            $table->foreign('user_account_base_id')->references('id')->on('user_account_base');
            $table->string('birth_date_str');
            $table->string('full_name');
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
        Schema::dropIfExists('user_account_profile');
    }
}
