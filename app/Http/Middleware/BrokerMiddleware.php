<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BrokerMiddleware
{
    /**
     * Handle an incoming request for broker portal.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated. Please log in as a partner broker.'
                ], 401);
            }

            return redirect()->guest(route('broker.login'))->with('error', 'Please log in with broker credentials to access your portal.');
        }

        $user = Auth::user();
        if (!$user || !$user->roles()->whereIn('slug', ['broker', 'super_admin', 'admin'])->exists()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden. Partner broker account required.'
                ], 403);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('broker.login')->with('error', 'Access Denied: Your account does not have partner broker access.');
        }

        // STRICT ENFORCEMENT: Only active brokers can access the portal
        if ($user->status !== 'active' || !$user->is_active) {
            Auth::logout();
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            $errMsg = match ($user->status) {
                'pending_verification' => 'Your partner broker account is awaiting verification.',
                'rejected' => 'Your broker application has been rejected.',
                'suspended' => 'Your partner broker account is currently suspended.',
                default => 'Your account is currently inactive. Only active brokers have portal access.',
            };

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errMsg
                ], 403);
            }

            return redirect()->route('broker.login')->with('error', $errMsg);
        }

        return $next($request);
    }
}
