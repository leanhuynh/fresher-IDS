<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Common\StatusCode;
use App\Common\Constant;
use Illuminate\Support\Facades\Auth;

class CheckHotelOwner
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
        $hotelId = $request->route('hotel'); // Lấy ID khách sạn từ route
        $hotel = Hotel::find($hotelId);  // Tìm khách sạn trong DB

        if (!$hotel) {
            abort(StatusCode::HTTP_STATUS_NOT_FOUND);
        }

        // Kiểm tra nếu user_id của hotel có khớp với user đang đăng nhập không
        if (!Auth::user() || !Auth::user()->role || Auth::user()->role->name !== Constant::ADMIN_ROLE_NAME && $hotel->owner_id !== Auth::id()) {
            abort(StatusCode::HTTP_STATUS_FORBIDDEN, 'You do not have permission to view this hotel.');
        }

        return $next($request); // Cho phép tiếp tục nếu hợp lệ
    }
}
