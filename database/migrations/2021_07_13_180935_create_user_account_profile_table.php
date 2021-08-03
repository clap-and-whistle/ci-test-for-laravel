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
            $table->string('birth_date_str');
            $table->string('full_name');
            $table->timestamps();
        });

        Schema::table('user_account_base', function (Blueprint $table) {
            $table->bigInteger('user_account_profile_id')->after('password');
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
        Schema::table('user_account_base', function (Blueprint $table) {
            $table->dropColumn('user_account_profile_id');
        });
    }
}
