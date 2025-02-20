<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // allow login using email or user name
    public function login(Request $request)
    {
        // Validate input values
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // get the user's account
        $user = \App\Models\User::where('email', $request->email)->first();

        // login
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            // update field last_login_at
            User::where('email', $request->get('email'))
                ->update([
                    'last_login_at' => now()
                ]);
            // notify user that login successfully
            session()->flash('success', __('auth.login.success'));
            return redirect()->intended($this->redirectPath()); // redirect after login successfully
        }

        // return the page with error that login failed
        return back()->withErrors(['email' => __('auth.login.fail')]);
    }

    protected function redirectTo() {
        return session()->pull('url.intended', '/users'); // if has no previous page, go to page list users
    }
}