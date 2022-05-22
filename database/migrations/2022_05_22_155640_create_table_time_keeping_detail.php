<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTimeKeepingDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_keeping_detail', function (Blueprint $table) {
            $table->id();
            $table->string('user_code');
            $table->text('detected_image_url')->nullable();
            $table->string('device_name')->nullable();
            $table->string('person_name')->nullable();
            $table->string('person_title')->nullable();
            $table->string('place_name')->nullable();
            $table->string('time')->nullable();
            $table->date('check_date')->nullable();
            $table->string('partner_id')->nullable();
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
        Schema::dropIfExists('time_keeping_detail');
    }
}
