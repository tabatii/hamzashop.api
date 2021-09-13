<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function forgot(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|max:190'
        ]);
        Password::sendResetLink($credentials);
        return response()->json();
    }

    public function reset(Request $request)
    {
        $credentials = $request->validate([
            'password' => 'required|string|min:6|max:100|confirmed',
            'password_confirmation' => 'required|string',
            'token' => 'required|string'
        ]);
        $status = Password::reset($credentials, function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        });
        if ($status == Password::INVALID_TOKEN) {
            return response()->json(['errors' => ['invalid' => __('passwords.token')]], 401);
        }
        return response()->json();
    }

    public function redirect(Request $request)
    {
    	return redirect()->away(env('APP_URL').'/auth/reset?token='.$request->token);
    }
}
