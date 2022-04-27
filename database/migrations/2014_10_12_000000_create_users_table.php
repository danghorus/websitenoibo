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
            $table->string('fullname');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('department');
            $table->timestamp('birthday');
            $table->string('position');
            $table->string('permission');
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('place_id')->nullable();
            $table->string('place_name')->nullable();
            $table->string('face_image_url')->nullable();
            $table->string('avatar');
            $table->string('password');
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
