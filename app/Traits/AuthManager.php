<?php

namespace Jiannius\Scaffold\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Jiannius\Scaffold\Requests\AuthResetPasswordRequest;

trait AuthManager
{
    /**
     * Show login page
     * 
     * @return \Illuminate\Http\Response
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Show register page
     * 
     * @return \Illuminate\Http\Response
     */
    public function showRegister()
    {
        return request()->ref
            ? view('auth.register', ['ref' => request()->ref])
            : redirect('/');
    }

    /**
     * Show forgot password page
     * 
     * @return \Illuminate\Http\Response
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Show reset password page
     * 
     * @return \Illuminate\Http\Response
     */
    public function showResetPassword()
    {
        return view('auth.reset-password', ['token' => request()->token]);
    }

    /**
     * Login
     * 
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        if (Auth::attempt(request()->only('email', 'password'), request()->filled('remember'))) {
            request()->session()->regenerate();
            return $this->authenticated(Auth::user());
        }

        return back()->withErrors([
            'login' => 'Unable to login. Please check your email or password',
        ]);
    }

    /**
     * Logout
     * 
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        $user = Auth::user();

        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return $this->loggedOut($user);
    }

    /**
     * Register
     * 
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        $user = $this->createUser(request());

        if ($user && $user->id) {
            if ($user instanceof MustVerifyEmail) {
                $user->sendEmailVerificationNotification();
            }

            Auth::login($user);

            return $this->registered($user);
        }
    }

    /**
     * Resend email verification
     * 
     * @return \Illuminate\Http\Response
     */
    public function resendEmailVerification()
    {
        if (request()->user() instanceof MustVerifyEmail && !request()->user()->hasVerifiedEmail()) {
            request()->user()->sendEmailVerificationNotification();
            return $this->verificationLinkSent(request()->user());
        }
    }

    /**
     * Verify email
     * 
     * @return \Illuminate\Http\Response
    */
    public function verifyEmail()
    {
        if (request()->user() instanceof MustVerifyEmail && !request()->user()->hasVerifiedEmail()) {
            request()->user()->markEmailAsVerified();
            return $this->emailVerified(request()->user());
        }
    }

    /**
     * Send password reset link
     * 
     * @return \Illuminate\Http\Response
     */
    public function sendPasswordResetLink()
    {
        $status = Password::sendResetLink(request()->only('email'));

        return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Reset password
     * 
     * @param AuthResetPasswordRequest $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(AuthResetPasswordRequest $request)
    {
        $request->validated();

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
    
                $user->saveQuietly();
            }
        );
    
        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}