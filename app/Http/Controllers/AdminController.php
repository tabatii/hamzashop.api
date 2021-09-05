<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['login']);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (! $token = auth()->guard('admin')->attempt($credentials)) {
            return response()->json(['errors' => ['auth' => __('auth.failed')]], 401);
        }
        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json();
    }

    public function logout()
    {
        auth()->guard('admin')->logout();
        return response()->json();
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->guard('admin')->refresh());
    }

    protected function respondWithToken($token)
    {
        $expires = auth()->factory()->getTTL() * 60;
        return response()->json([
            'token' => $token,
            'expires' => $expires
        ]);
    }
}
