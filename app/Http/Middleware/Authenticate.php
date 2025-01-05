<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {

        if ($request->expectsJson()) {
            return null; // For API responses, return null to trigger a 401 Unauthorized response
        }

        // Determine the guard and redirect appropriately
        $guard = $this->getCurrentGuard();

        if ($guard == 'admin') {
            return route('admin.login'); // Redirect to the admin login route
        }

        return route('login'); // Redirect to the default web login route
    }

    /**
     * Determine the current guard being used.
     */
    protected function getCurrentGuard(): string
    {
        // Check the guard for the current route
        if (auth('admin')->check()) {
            return 'admin';
        }

        return 'web'; // Default to 'web' guard
    }
}
