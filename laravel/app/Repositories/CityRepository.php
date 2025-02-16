<?php 

namespace App\Repositories;
use App\Repositories\Interface\CityRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use App\Common\Constant;
use App\Models\Role;
use Exception;

class CityRepository implements CityRepositoryInterface 
{
    protected $_model;

    // constructor
    public function __construct() {
        $this->_model = app()->make(\App\Models\City::class);
    }

    public function getAll() {
        return $this->_model::all();
    }
}