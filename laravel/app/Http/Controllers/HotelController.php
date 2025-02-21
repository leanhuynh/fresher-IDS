<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Services\HotelService;
use App\Http\Request\HotelRequest;
use App\Services\CityService;
use Illuminate\Http\Request;
use App\Common\StatusCode;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Exception;

class HotelController extends Controller
{
    protected HotelService $_hotelService;
    protected CityService $_cityService;

    public function __construct(HotelService $hotelService, CityService $cityService)
    {
        $this->_hotelService = $hotelService;
        $this->_cityService = $cityService;
    }

    public function index(Request $request)
    {
        try {
            $owner_id = Auth::user()->id;
            $cities = $this->_cityService->getAll();
            $hotels = $this->_hotelService->getHotelsByOwnerId($owner_id);

            if ($request->ajax()) {
                return response()->json([
                    'hotels' => $hotels,
                    'paginationHtml' => $hotels->links('vendor.pagination.custom')->render(),
                ]);
            }
            return view('hotels.index', compact('hotels', 'cities'));
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized access: ' . $e->getMessage());
            return view('error.default', [
                'status' => StatusCode::HTTP_STATUS_FORBIDDEN,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return view('error.default', [
                'status' => StatusCode::HTTP_STATUS_NOT_FOUND,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function createHotel()
    {
        try {
            $cities = $this->_cityService->getAll();
            Log::info('Create hotel');
            return view('hotels.create', compact('cities'));
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized access: ' . $e->getMessage());
            return view('error.default', [
                'status' => StatusCode::HTTP_STATUS_FORBIDDEN,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return view('error.default', [
                'status' => StatusCode::HTTP_STATUS_NOT_FOUND,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function createHotelAPI(HotelRequest $request) 
    {
        try {
            $owner_id = Auth::user()->id;
            $hotel = $this->_hotelService->createHotel($request->validated(), $owner_id);
            log::info("create hotel successfully with id : {$hotel->id} and owner id : {$hotel->owner_id}");
            return redirect()->to('/hotels')->with('success', __('messages.hotel.create.success'));
        } catch (Exception $e) {
            log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function viewHotel($hotel_id)
    {
        try {
            $hotel = $this->_hotelService->findHotelById($hotel_id);
            Log::info("View hotel with id {$hotel_id}");
            return view('hotels.view', compact('hotel'));
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized access: ' . $e->getMessage());
            return view('error.default', [
                'status' => StatusCode::HTTP_STATUS_FORBIDDEN,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return view('error.default', [
                'status' => StatusCode::HTTP_STATUS_NOT_FOUND,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function editHotel($hotel_id)
    {
        try {
            $cities = $this->_cityService->getAll();
            $hotel = $this->_hotelService->findHotelById($hotel_id);
            return view('hotels.edit', compact('hotel', 'cities'));
        } catch (AuthorizationException $e) {
            Log::warning('Unauthorized access: ' . $e->getMessage());
            return view('error.default', [
                'status' => StatusCode::HTTP_STATUS_FORBIDDEN,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return view('error.default', [
                'status' => StatusCode::HTTP_STATUS_NOT_FOUND,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function editHotelAPI(HotelRequest $request, $hotel_id)
    {
        try {
            $hotel = $this->_hotelService->updateHotel($request->validated(), $hotel_id);
            log::info("edit hotel successfully with id : {$hotel->id} and owner id : {$hotel->owner_id}");
            return redirect()->back()->with('success', __('messages.hotel.update.success'));
        } catch (Exception $e) {
            log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
