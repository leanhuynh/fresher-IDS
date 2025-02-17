<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Common\StatusCode;
use App\Common\Constant;

class CheckIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra vai trò của người dùng
        if (Auth::user() && Auth::user()->role && Auth::user()->role->name !== Constant::ADMIN_ROLE_NAME) {
            // Nếu không phải admin, trả về lỗi 403
            abort(StatusCode::HTTP_STATUS_FORBIDDEN, 'You do not have permission to view this page.');
        }

        return $next($request);
    }
}
