<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Auth\Services\AuthService;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function __construct(private AuthService $auth) {}

    public function register(RegisterRequest $request)
    {
        $result = $this->auth->register($request->validated());

        return response()->json($result, 201);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->auth->login($request->validated());

        return response()->json($result, 201);
    }

    public function me(Request $request)
    {
         return response()->json(['user' => $request->user()], 200);
    }

    public function logout(Request $request)
    {
        $this->auth->logoutCurrent($request->user());

        return response()->json(['ok' => true], 200);
    }
}
