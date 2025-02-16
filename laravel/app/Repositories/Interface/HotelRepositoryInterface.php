<?php

namespace App\Repositories\Interface;

interface HotelRepositoryInterface 
{
    public function getHotelsByOwnerId($hotel_id);

    public function findHotelById($hotel_id, $owner_id);

    public function createHotel(array $data);

    public function searchHotels(array $filter);

    public function updateHotel(array $data, $hotel_id);

    public function deleteHotel($hotel_id, $owner_id);
}