<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function change_password(Request $request, User $users)
    {
        $request->validate([
          'password' => ['required', 'string', 'confirmed'],
      ]);

        $users->update([
          'password' => Hash::make($request->password)
      ]);
        $users = User::all();

        return redirect()->route('users.index', compact('users'))->with('message', 'User Password Updated Succesfully');

    }
}
