<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Common\StatusCode;
use App\Models\User;
use App\Http\Request\HotelRequest;
use Illuminate\Support\Facades\Hash;
use App\Services\HotelService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Http\Controllers\Controller;

class HotelControllerAPI extends Controller
{
    protected HotelService $_hotelService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(HotelService $hotelService)
    {
        // $this->middleware('auth');
        $this->_hotelService = $hotelService;
    }

    public function create(HotelRequest $request) {
        try {
            $hotel = $this->_hotelService->createHotel($request->validated());
            log::info("create hotel successfully with id : {$hotel->id} and owner id : {$hotel->owner_id}");
            return response()->json(['hotel' => $hotel, 'message' => 'Create hotel successfully!!'], StatusCode::HTTP_STATUS_CREATED);
        } catch (Exception $e) {
            log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], StatusCode::HTTP_STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    public function searchHotels(Request $request) {
        try {
            $filters = $request->only(['owner_id', 'city_id', 'hotel_code', 'name_en']);
            // query
            $hotels = $this->_hotelService->searchHotels($filters);
            log::info("search hotels with filter " . json_encode($filters));
            return response()->json([
                'hotels' => $hotels, 
                'message' => 'Search hotels successfully!!',
                'paginationHtml' => $hotels->links('vendor.pagination.custom')->render(), // Gửi HTML phân trang
                ], StatusCode::HTTP_STATUS_OK);
        } catch (Exception $e) {
            log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], StatusCode::HTTP_STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(HotelRequest $request, $hotel_id) {
        try {
            $hotel = $this->_hotelService->updateHotel($request->validated(), $hotel_id);
            log::info("edit hotel successfully with id : {$hotel->id} and owner id : {$hotel->owner_id}");
            return response()->json(['hotel' => $hotel, 'message' => 'Edit hotel successfully!!'], StatusCode::HTTP_STATUS_OK);
        } catch (Exception $e) {
            log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], StatusCode::HTTP_STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request, $hotel_id) {
        try {
            $owner_id = $request->input('owner_id');
            $this->_hotelService->deleteHotel($hotel_id, $owner_id);
            log::info("delete hotel successfully with id : {$hotel_id} and owner id : {$owner_id}");
            return response()->json(['message' => 'Delete hotel successfully!!'], StatusCode::HTTP_STATUS_OK);
        } catch (Exception $e) {
            log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], StatusCode::HTTP_STATUS_INTERNAL_SERVER_ERROR);
        }
    }
}