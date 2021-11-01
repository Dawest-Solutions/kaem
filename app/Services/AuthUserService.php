<?php

namespace App\Services;


use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\RegisterByCodeRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthUserService
{
    /**
     * @param LoginRequest $validated
     * @return User|null
     */
    public static function loginUser(LoginRequest $validated): ?User
    {
        if (!Auth::attempt([
            'email' => $validated->input('email'),
            'password' => $validated->input('password'),
        ])) {
            return null;
        }
        return Auth::user();
    }

    public static function logout()
    {
        Auth::user()->tokens()->delete();
    }

    /**
     * @throws ValidationException
     */
    public static function searchUserByRegisterCode(RegisterByCodeRequest $request): User
    {
        return User::with('pos')->registerByCode($request->input('code'))->firstOrFail();
    }

    /**
     * @param RegisterByCodeRequest $request
     * @return User
     * @throws ValidationException
     */
    public static function registerUserByCode(RegisterByCodeRequest $request): User
    {
        $user = self::searchUserByRegisterCode($request);

        // Remove the registry code.
        $user->register_code = null;

        $user->password = Hash::make($request->input('password'));
        $user->birth_date = Carbon::create($request->input('birth_date'))->format('Y-m-d');
        $user->street = $request->input('street');
        $user->apartment_number = $request->input('apartment_number');
        $user->postal_code = $request->input('postal_code');
        $user->city = $request->input('city');
        $user->borough = $request->input('borough');
        $user->district = $request->input('district');
        $user->voivodeship = $request->input('voivodeship');
        $user->tax_office = $request->input('tax_office');
        $user->agreements = $request->only('agreement_1', 'agreement_2', 'agreement_1_text', 'agreement_2_text');

        $user->save();

        return $user;
    }

    public static function forgotPassword(ForgotPasswordRequest $request): bool
    {
        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT;
    }

    public static function resetPassword(ForgotPasswordRequest $request): bool
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET;
    }

}
