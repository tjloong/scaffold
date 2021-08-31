<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rule;
use App\Providers\RouteServiceProvider;
use Jiannius\Scaffold\Traits\AuthManager;

class AuthController extends Controller
{
    use AuthManager;
    
    /**
     * Custom action after login process is done
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function authenticated($user)
    {
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Validate and create a newly registered user
     * 
     * @param Illuminate\Http\Request $request
     * @return mixed
     */
    public function createUser($request)
    {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
            ],
        ])->validate();

        return User::create([
            'name' => $request('name'),
            'email' => $request('email'),
            'password' => bcrypt($request('password')),
        ]);
    }

    /**
     * Custom action after register process is done
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function registered($user)
    {
        return $this->request->wantsJson()
            ? $user
            : redirect(RouteServiceProvider::HOME);
    }

    /**
     * Custom action after user is logged out
     * 
     * @return \Illuminate\Http\Response
     */
    public function loggedOut()
    {
        return $this->request->wantsJson()
            ? response()->json(true)
            : redirect('/');
    }

    /**
     * Custom action after email verification link is sent
     *
     * @return \Illuminate\Http\Response
     */
    public function verificationLinkSent($user)
    {
        return $this->request->wantsJson()
            ? response()->json(true)
            : back()->with('status', 'successful');
    }

    /**
     * Custom action after email is verified
     *
     * @return \Illuminate\Http\Response
     */
    public function emailVerified($user)
    {
        return $this->request->wantsJson()
            ? response()->json(true)
            : redirect(RouteServiceProvider::HOME);
    }
}