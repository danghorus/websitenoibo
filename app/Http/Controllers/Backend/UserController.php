<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::all();
        if ($request->has('search')) {
            $users = User::where('fullname', 'like', "%{$request->search}%")->get();
        }
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        //Lưu hình thẻ khi có file hình
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
        }

        User::create([
            'fullname' => $request->fullname,
            'date_official' => $request->date_official,
            'avatar' => $avatar_name,
            'phone' => $request->phone,
            'birthday' => $request->birthday,
            'department' => $request->department,
            'email' => $request->email,
            'position' => $request->position,
            'permission' => $request->permission,
            'check_type' => $request->check_type,
            'password' => Hash::make($request->password),
        ]);


        return redirect()->route('users.index')->with('message', 'User Register Succesfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update([

            'fullname' => $request->fullname,
            'date_official' => $request->date_official,
            'phone' => $request->phone,
            'birthday' => $request->birthday,
            'email' => $request->email,
            'department' => $request->department,
            'position' => $request->position,
            'permission' => $request->permission,
            'check_type' => $request->check_type
        ]);
        return redirect()->route('users.index', compact('user'))->with('message', 'User Updated Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (auth()->user()->id == $user->id) {
            return redirect()->route('users.index')->with('message', 'You are deleting yourself.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('message', 'User Deleted Succesfully');
    }
}
