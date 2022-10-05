<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    public function index()
    {

        $users = User::all();
        $password = Project::all();
        return view('change_password', compact('users','project'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu cũ không chính xác!');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $users = User::all();

        return redirect()->route('home', compact('users'))->with('message', 'Thay đổi mật khẩu thành công!');
    }
}
