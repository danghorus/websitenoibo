<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
            'user_make', // Người giao việc
            'user_bug',  // Người bắt Bugg 
            'user_fix',  // Người thực hiện công việc hoặc fix bug
            'project_id', // ID project
            'parents_id', //ID công việc cha
            'work_name', //Tên công việc
            'work_status', // Trạng thái công việc
            'work_detail', // Chi tiết công việc
            'get_time', // Thời gian nhận việc
            'start_time', // Thời gian bắt đầu làm
            'wait_time', // Mốc thời gian click button tạm dừng
            'wait_value', // Tổng thời gian tạm dừng
            'end_time', // Hạn chót công việc
            'duration', // Thời lượng để thực hiện công việc
            'importance' // Trọng số
    ];
}
