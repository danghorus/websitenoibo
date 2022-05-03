<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeKeepingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_keeping', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('checkin')->nullable();
            $table->string('checkout')->nullable();
            $table->date('check_date')->nullable();
            $table->string('reason')->nullable();
            $table->integer('check_type')->default(1)->comment('1: Chấm công qua cam, 2 chấm công thủ công');
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
        Schema::dropIfExists('time_keeping');
    }
}
