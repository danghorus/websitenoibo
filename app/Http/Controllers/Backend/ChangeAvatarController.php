<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ChangeAvatarController extends Controller

{


    public function change_avatar(Request $request, User $users)
    {
      $avatar_name = '';
      if($request->hasFile('avatar')){
      //Hàm kiểm tra dữ liệu
      $this->validate($request, 
        [
          //Kiểm tra đúng file đuôi .jpg,.jpeg,.png.gif và dung lượng không quá 2M
          'avatar' => 'mimes:jpg,jpeg,png,gif|max:2048',
        ],			
        [
          //Tùy chỉnh hiển thị thông báo không thõa điều kiện
          'avatar.mimes' => 'Chỉ chấp nhận ảnh đại diện với đuôi .jpg .jpeg .png .gif',
          'avatar.max' => 'Ảnh đại diện giới hạn dung lượng không quá 2M',
        ]
      );
      
      //Lưu hình ảnh vào thư mục public/upload/hinhthe
      $avatar = $request->file('avatar');
      $avatar_name = time().'_'.$avatar->getClientOriginalName();
      $destinationPath = public_path('image');
      $avatar->move($destinationPath, $avatar_name);
          

        $users->update([
            'avatar' => $avatar_name,
      ]);
      }
        $users = User::all();

        return redirect()->route('users.index', compact('users'))->with('message', 'User Avatar Updated Succesfully');
      }
}
