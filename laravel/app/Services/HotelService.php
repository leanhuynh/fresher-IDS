<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use App\Repositories\HotelRepository;
use Exception;

class HotelService {

    protected HotelRepository $_hotelRepository;

    public function __construct(HotelRepository $hotelRepository) {
        $this->_hotelRepository = $hotelRepository;
    }

    public function getHotelsByOwnerId(array $filters, $owner_id) {
        return $this->_hotelRepository->getHotelsByOwnerId($filters, $owner_id);
    }

    public function findHotelById($hotel_id) {
        return $this->_hotelRepository->findHotelById($hotel_id);
    }

    public function createHotel(array $data, $owner_id) {
        return $this->_hotelRepository->createHotel($data, $owner_id);
    }

    public function searchHotels(array $filter) {
        return $this->_hotelRepository->searchHotels($filter);
    }

    public function updateHotel(array $data, $hotel_id) {
        return $this->_hotelRepository->updateHotel($data, $hotel_id);
    }

    public function deleteHotel($hotel_id, $owner_id) {
        return $this->_hotelRepository->deleteHotel($hotel_id, $owner_id);
    }
}