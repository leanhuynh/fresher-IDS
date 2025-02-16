<?php

namespace App\Services;
use App\Repositories\CityRepository;

class CityService {

    protected CityRepository $_cityRepository;

    public function __construct(CityRepository $cityRepository) {
        $this->_cityRepository = $cityRepository;
    }

    public function getAll() {
        return $this->_cityRepository->getAll();
    }
}