<?php

namespace App\Http\Controllers;


use App\Http\Requests\ChangePasswordRequest;
use App\User;

class AuthController extends Controller
{

    public function changePasswordView() {
        return view('auth.change-password');
    }

    public function changePassword(ChangePasswordRequest $request) {
        $user = auth()->user();

        $user->password = bcrypt($request->new_password);
        $user->save();

        session()->flash('status', 'Password Berhasil dirubah');

        return redirect(route("home"));
    }
}
