<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated. Please log in as an administrator.'
                ], 401);
            }

            return redirect()->guest(route('admin.login'))->with('error', 'Please log in with administrator credentials to access the console.');
        }

        $user = Auth::user();
        if (!$user || !$user->roles()->whereIn('slug', ['super_admin', 'admin'])->exists()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden. Administrator privileges required.'
                ], 403);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->with('error', 'Access Denied: Your account does not have administrator privileges.');
        }

        // STRICT ENFORCEMENT: Only active admins can access the console
        if ($user->status !== 'active' || !$user->is_active) {
            Auth::logout();
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            $errMsg = 'Access Denied: This administrator account is currently suspended or inactive.';

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errMsg
                ], 403);
            }

            return redirect()->route('admin.login')->with('error', $errMsg);
        }

        return $next($request);
    }
}
