<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petitions', function (Blueprint $table) {
            $table->id();
            $table->string('user_fullname');
             $table->string('user_id');
            $table->string('petition_type');
            $table->time('checkin');
            $table->date('check_date');
            $table->time('checkout');
            $table->date('date_to');
            $table->string('type_leave');
            $table->string('reason');
            $table->string('petition_status');
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
        Schema::dropIfExists('petitions');
    }
}
