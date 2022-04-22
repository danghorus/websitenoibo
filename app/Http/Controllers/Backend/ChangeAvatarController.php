<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ChangeAvatarController extends Controller
{
    public function change_avatar(Request $request, User $user)
    {
        $request->validate([
          'avatar' => ['nullable', 'image', 'max:1024'],
      ]);

        $user->update([
          'avatar' => $request->avatar,
      ]);

        return redirect()->route('users.index')->with('message', 'User Avatar Updated Succesfully');
    }
}
