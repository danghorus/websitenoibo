<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Petition;
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
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        if ($request->has('search')) {
            $users = User::where('fullname', 'like', "%{$request->search}%")->get();
        }
        return view('users.index', compact('users','petitions1', 'petitions01'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        return view('users.create',compact('users','petitions1', 'petitions01'));
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
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();

        return redirect()->route('users.index',compact('users','petitions1', 'petitions01'))->with('message', 'User Register Successfully');

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
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        return view('users.edit', compact('user', 'users','petitions1', 'petitions01'));
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

        return redirect()->route('users.index', compact('user'))->with('message', 'User Updated Successfully');
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
        return redirect()->route('users.index')->with('message', 'User Deleted Successfully');
    }

    public function all_user(Request $request)
    {
        $department = $request->input('task_department');

        $AuthDepartment = Auth::user()->department;

        $builder = User::query()->select(['id', 'fullname', 'department', 'user_code', 'place_id']);
        if($department == 12){
            $builder->where('department', '=', $AuthDepartment);
        }else if($department != null){
           $builder->where('department', '=', $department);
        }else{
            $builder;
        }
        $users = $builder->get();
        
        return [
            'code' => 200,
            'data' => $users
        ];
    }

    public function user(Request $request)
    {
        $department = $request->input('task_department');

        $AuthDepartment = Auth::user()->department;

        $builder = User::query()->select(['id', 'fullname', 'department', 'user_code', 'place_id']);
        if($department == 12){
            $builder->where('department', '=', $AuthDepartment);
        }else if($department != null){
           $builder->where('department', '=', $department);
        }else{
            $builder;
        }
        $users = $builder->get();
        
        return [
            'code' => 200,
            'data' => $users
        ];
    }

    public function all_user_by_group()
    {
        $users = User::query()->select(['id', 'fullname', 'user_code', 'place_id', 'department'])->get();

        $newUsers = [];

        foreach ($users as $user) {
            if($user->department == 1){
                $user->department_lable = "Admin";
            }
            else if($user->department == 2){
                $user->department_lable = "Dev";
            }
            else if($user->department == 3){
                $user->department_lable = "Game Design";
            }
            else if($user->department == 4){
                $user->department_lable = "Art";
            }
            else if($user->department == 5){
                $user->department_lable = "Tester";
            }
            else if($user->department == 6){
                $user->department_lable = "Điều hành";
            }
            else if($user->department == 7){
                $user->department_lable = "Hành chính nhân sự";
            }
            else if($user->department == 8){
                $user->department_lable = "Kế toán";
            }
            else if($user->department == 9){
                $user->department_lable = "Phân tích dữ liệu";
            }
            else if($user->department == 10){
                $user->department_lable = "Support";
            }
            else if($user->department == 11){
                $user->department_lable = "Marketing";
            }
            if (isset($newUsers[$user->department])) {
                $newUsers[$user->department]['values'][] = $user;
            } else {
                $newUsers[$user->department] = [
                    'department' => $user->department_lable,
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
