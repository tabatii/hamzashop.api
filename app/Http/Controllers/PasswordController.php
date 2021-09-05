<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class PasswordController extends Controller
{

    public function forgot(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email|max:190']);

        Password::sendResetLink($credentials);

        return response()->json(['message' => __('passwords.sent')]);
    }

    public function reset(Request $request)
    {
        $credentials = $request->validate([
            'password' => 'required|string|min:6|max:190|confirmed',
            'password_confirmation' => 'string|min:6|max:190',
            'token' => 'required|string'
        ]);

        $status = Password::reset($credentials, function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        });

        if ($status == Password::INVALID_TOKEN) {
            return response()->json(['error' => __('passwords.token')], 401);
        }

        return response()->json(['message' => __('passwords.reset')]);
    }

    public function redirect(Request $request)
    {
    	return redirect()->away(config('app.front_url').'/password/reset?token='.$request->token);
    }
}
