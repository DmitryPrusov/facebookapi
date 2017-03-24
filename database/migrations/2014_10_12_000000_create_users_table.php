<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id')->unique();
            $table->bigInteger('facebook_id')->unique();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('password')->nullable();
          //  $table->string('accessToken')->nullable();

         //   $table->bigInteger('facebook_user_id')->unsigned()->index();
            // Normally you won't need to store the access token in the database
            $table->string('access_token')->nullable();


            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
