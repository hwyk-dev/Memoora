<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if ($user->isBanned()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = 'Your account has been banned.';
            if ($user->ban_reason) {
                $message .= ' Reason: ' . $user->ban_reason;
            }

            return redirect()->route('login')->withErrors(['email' => $message]);
        }

        if ($user->isSuspended()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account is suspended until ' . $user->suspended_until->format('M j, Y g:i A') . '.',
            ]);
        }

        return $next($request);
    }
}
