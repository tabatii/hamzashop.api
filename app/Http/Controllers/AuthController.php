<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\NotificationService;
use App\Models\Notification;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['register', 'login']);
        $this->middleware('guest')->only(['register', 'login']);
    }

    public function register(RegisterRequest $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $notification = new Notification;
        $notification->icon = 'mdi-account-plus';
        $notification->content = (new NotificationService)->newUser();
        $notification->save();

        $token = auth()->login($user);
        $user->sendEmailVerificationNotification();
        return $this->respondWithToken($token);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['errors' => ['auth' => __('auth.failed')]], 401);
        }
        return $this->respondWithToken($token);
    }

    public function me()
    {
        return new UserResource(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json();
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        $expires = auth()->factory()->getTTL() * 60;
        return response()->json([
            'user' => new UserResource(auth()->user()),
            'token' => $token,
            'expires' => $expires
        ]);
    }
}
