<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->string('user_make')->nullable(); // Người giao việc
            $table->string('user_bug')->nullable();  // Người bắt Bugg 
            $table->string('user_fix')->nullable();  // Người thực hiện công việc hoặc fix bug
            $table->string('project_id')->nullable();// ID project
            $table->string('parents_id')->nullable();//ID công việc cha
            $table->string('work_status')->nullable()->default(1);// Trạng thái công việc
            $table->string('work_detail')->nullable();// Chi tiết công việc
            $table->datetime('get_time')->nullable();// Thời gian nhận việc
            $table->date('start_time')->nullable();// Thời gian bắt đầu làm
            $table->date('wait_time')->nullable();// Mốc thời gian click button tạm dừng
            $table->string('wait_value')->nullable();// Tổng thời gian tạm dừng
            $table->datetime('end_time')->nullable();// Hạn chót công việc
            $table->string('duration')->nullable();// Thời lượng để thực hiện công việc
            $table->string('importance')->nullable();// Trọng số
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
        Schema::dropIfExists('works');
    }
}
