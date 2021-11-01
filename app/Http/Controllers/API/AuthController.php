<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterByCodeRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\RegisterByCodeResource;
use App\Services\AuthUserService;
use Exception;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = AuthUserService::loginUser($request);

        if ($user) {
            return $this->success([
                'token' => $user->createToken('LOGIN TOKEN API')->plainTextToken,
                'name' => $user->name,
            ]);
        }

        return $this->error(__('Incorrect login details.'), 422);
    }

    /**
     * @param $code
     * @return JsonResponse
     */
    public function searchRegisterByCode(RegisterByCodeRequest $request): JsonResponse
    {
        try {
            $user = AuthUserService::searchUserByRegisterCode($request);

            return $this->success(new RegisterByCodeResource($user));
        } catch (Exception $ex) {
            return $this->error(__('Server Error.'));
        }
    }

    /**
     * @param RegisterByCodeRequest $request
     * @return JsonResponse
     */
    public function registerUserByCode(RegisterByCodeRequest $request): JsonResponse
    {
        try {
            AuthUserService::registerUserByCode($request);

            return $this->success([], __('Registered.'));
        } catch (Exception $ex) {
            return $this->error(__('Server Error.'));
        }
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        AuthUserService::logout();

        return $this->success(__('Successfully logged out.'));
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            AuthUserService::forgotPassword($request);

            return $this->success([], __('Password reset email sent.'));
        } catch (Exception $ex) {
            return $this->error(__('Server Error.'));
        }
    }

    public function resetPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            AuthUserService::resetPassword($request);
            
            return $this->success([], __('Password successfully reset.'));
        } catch (Exception $ex) {
            return $this->error(__('Server Error.'));
        }
    }
}
