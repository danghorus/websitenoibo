<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Image;

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
	if($request->hasFile('avatar')){
		//Hàm kiểm tra dữ liệu
		//Lưu hình ảnh vào thư mục public/upload/hinhthe
		$avatar = $request->file('avatar');
		$destinationPath = public_path('image');
        $filename = $avatar->getClientOriginalName();
		$avatar->move($destinationPath, $filename);
        }


        User::create([
            'fullname' => $request->fullname,
            'avatar' => $request->avatar,
            'phone' => $request->phone,
            'birthday' => $request->birthday,
            'department' => $request->department,
            'email' => $request->email,
            'position' => $request->position,
            'permission' => $request->permission,
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
            'phone' => $request->phone,
            'birthday' => $request->birthday,
            'email' => $request->email,
            'department' => $request->department,
            'position' => $request->position,
            'permission' => $request->permission
        ]);

        return redirect()->route('users.index')->with('message', 'User Updated Succesfully');
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
