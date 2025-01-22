<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login'); // Update the view path if necessary
    }

    // Handle login requests
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $this->clearLoginAttempts($request);
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    // Log the user out
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login'); // Update the route name if necessary
    }

    // Validate login credentials
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);
    }

    // Attempt to log the user in
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);

        $user = $this->guard()->getProvider()->retrieveByCredentials($credentials);

        if ($user && Hash::check($request->password, $user->password)) {
            // Log in the user manually
            $this->guard()->login($user, $request->boolean('remember'));

            // Return true to indicate successful login
            return true;
        }

        return false; // Return false if login fails
    }




    // Get the login credentials from the request
    protected function credentials(Request $request)
    {
        return $request->only('username', 'password');
    }

    // Send a successful login response
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();


        return redirect()->intended($this->redirectPath());
    }
    protected function redirectPath()
    {
        return route('admin.dashboard'); // Replace with your dashboard route
    }

    // Send a failed login response
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'username' => [trans('auth.failed')],
        ]);
    }

    // Redirect path after login


    // Throttle login attempts
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return app('Illuminate\Cache\RateLimiter')->tooManyAttempts(
            $this->throttleKey($request), 5, 1 // 5 attempts per minute
        );
    }

    protected function incrementLoginAttempts(Request $request)
    {
        app('Illuminate\Cache\RateLimiter')->hit($this->throttleKey($request));
    }

    protected function clearLoginAttempts(Request $request)
    {
        app('Illuminate\Cache\RateLimiter')->clear($this->throttleKey($request));
    }

    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('username')) . '|' . $request->ip();
    }

    protected function fireLockoutEvent(Request $request)
    {
        event(new \Illuminate\Auth\Events\Lockout($request));
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = app('Illuminate\Cache\RateLimiter')->availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'username' => [trans('auth.throttle', ['seconds' => $seconds])],
        ]);
    }

    // Handle post-login actions
    protected function authenticated(Request $request, $user)
    {
        return redirect()->route('admin.dashboard'); // Redirect after login
    }

    // Specify the username field for login
    public function username()
    {
        return 'username'; // Specify the username field
    }

    // Get the guard for admin
    protected function guard()
    {
        return Auth::guard('admin'); // Ensure the 'admin' guard is set up in `config/auth.php`
    }
}
