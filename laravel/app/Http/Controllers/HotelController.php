<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Services\HotelService;
use App\Services\CityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Exception;

class HotelController extends Controller
{
    protected HotelService $_hotelService;

    public function __construct(HotelService $hotelService, CityService $cityService)
    {
        // $this->middleware('auth');
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
        } catch (Exception $e) {
            log::error($e->getMessage());
            session()->flash('error', $e->getMessage());
            abort(404);
        }
    }

    public function createHotel()
    {
        try {
            $cities = $this->_cityService->getAll();
            log::info('create hotel');
            return view('hotels.create', compact('cities'));
        } catch (Exception $e) {
            log::error($e->getMessage());
            session()->flash('error', $e->getMessage());
            abort(404);
        }
    }

    public function viewHotel($hotel_id) 
    {
        try {
            $hotel = $this->_hotelService->findHotelById($hotel_id);
            log::info("view hotel with id {$hotel_id}");
            return view('hotels.view', compact('hotel'));
        } catch (Exception $e) {
            log::error($e->getMessage());
            session()->flash('error', $e->getMessage());
            $status = 404;
            $message = $e->getMessage();
            return view('error.default', compact(['status', 'message']));
        }
    }

    public function editHotel($hotel_id) {
        try {
            $cities = $this->_cityService->getAll();
            $hotel = $this->_hotelService->findHotelById($hotel_id);
            return view('hotels.edit', compact('hotel', 'cities'));
        } catch (Exception $e) {
            log::error($e->getMessage());
            session()->flash('error', $e->getMessage());
            abort(404);
        }
    }
}