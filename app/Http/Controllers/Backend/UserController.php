<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Models\Project;
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
        $projects = Project::all();
        if ($request->has('search')) {
            $users = User::where('fullname', 'like', "%{$request->search}%")->get();
        }
        return view('users.index', compact('users', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $projects = Project::all();
        return view('users.create',compact('users','projects'));
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
            'user_status' => $request->user_status,
            'password' => Hash::make($request->password),
        ]);

        $users = User::all();
        $projects = Project::all();

        return redirect()->route('users.index',compact('users', 'projects'))->with('message', 'User Register Succesfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $users = User::all();
        $projects = Project::all();
        return view('users.edit', compact('user','projects', 'users'));
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
            'check_type' => $request->check_type,
            'user_status' => $request->user_status,
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

    public function all_user()
    {
        $users = User::query()->select(['id', 'fullname', 'user_code', 'place_id'])->where('user_status', '=', 1)->get();

        return [
            'code' => 200,
            'data' => $users
        ];
    }

    public function all_user_by_group()
    {
        $users = User::query()->select(['id', 'fullname', 'user_code', 'place_id', 'department'])->where('user_status', '=', 1)->get();

        $newUsers = [];

        foreach ($users as $user) {
            if (isset($newUsers[$user->department])) {
                $newUsers[$user->department]['values'][] = $user;
            } else {
                $newUsers[$user->department] = [
                    'department' => $user->department,
                    'values' => []
                ];
            }
        }

        return [
            'code' => 200,
            'data' => array_values($newUsers)
        ];
    }
}
