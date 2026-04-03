<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If no specific role required, allow any authenticated user
        if (!$role) {
            return $next($request);
        }

        // Check specific roles
        switch ($role) {
            case 'admin':
                if (!$user->isAdmin()) {
                    abort(403, 'Bạn không có quyền truy cập trang này.');
                }
                break;

            case 'approver':
                if (!$user->isApprover() && !$user->isAdmin()) {
                    abort(403, 'Bạn không có quyền phê duyệt.');
                }
                break;

            case 'employee':
                // All authenticated users can access employee features
                break;

            default:
                abort(403, 'Quyền truy cập không hợp lệ.');
        }

        return $next($request);
    }
}