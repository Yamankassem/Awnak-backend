<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Services\AuthService;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function __construct(private AuthService $auth) {}

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request Validated registration data.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->auth->register($request->validated());

        return static::success(
            data: $user,
            message: 'auth.registered',
            status: 201
        );
    }

    /**
     * Authenticate user credentials.
     *
     * @param LoginRequest $request Validated login credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function login(LoginRequest $request)
    {
        $user =$this->auth->login($request->validated());

        return static::success(
            data: $user,
            message: 'auth.logged_in'
        );
    }

    /**
     * Get currently authenticated user.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return static::success(
            data: $request->user(),
            message: 'auth.me'
        );
    }

     /**
     * Logout the current user.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->auth->logoutCurrent($request->user());

        return static::success(
            message: 'auth.logged_out'
        );
    }
}
