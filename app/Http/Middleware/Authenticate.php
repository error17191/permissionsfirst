<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate extends Middleware
{

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        parent::__construct($auth);
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if ($authId = request()->header('auth-id')) {
            $user = \App\User::find($authId);
            if (!$user) {
                return response()->json([
                    'status' => 'invalid_auth_data'
                ], 422);
            }
            auth()->login($user);
        }

        return parent::handle($request, $next, $guards); // TODO: Change the autogenerated stub
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
