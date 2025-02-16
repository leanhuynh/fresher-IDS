<?php 

namespace App\Repositories;
use App\Repositories\Interface\HotelRepositoryInterface;
use App\Http\Request\HotelRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Common\Constant;
use App\Models\Role;
use Exception;

class HotelRepository implements HotelRepositoryInterface 
{
    protected $_model;

    // constructor
    public function __construct() {
        $this->_model = app()->make(\App\Models\Hotel::class);
    }

    public function getHotelsByOwnerId($owner_id) 
    {
        try {
            $hotels = $this->_model::with(['user', 'city'])->where('owner_id', $owner_id)->paginate(Constant::PAGINATE_DEFAULT);
            return $hotels;
        } catch(Exception $e) {
            // throw new Exception($e->getMessage());
            throw new Exception('database has some errors!!');
        }
    }

    public function findHotelById($hotel_id, $owner_id) {
        try {
            // Lấy thông tin của hotel theo id
            $hotel = $this->_model::with('city')->findOrFail($hotel_id);

            // Kiểm tra xem user có quyền truy cập hotel này không
            if ($hotel->owner_id != $owner_id) {
                abort(403, 'You do not have permission to access this hotel');
            }

            return $hotel;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('Hotel not found');
        } catch (\Illuminate\Database\QueryException $e) {
            throw new Exception('Database has some errors!!');
        } catch (Exception $e) {
            throw $e; // Giữ nguyên thông báo lỗi khi không có quyền truy cập
        }
    }

    public function createHotel(array $data) {
        try {
            // Tạo mới hotel
            $newHotel = $this->_model::create([
                'owner_id' => $data['owner_id'],
                'city_id' => $data['city_id'],
                'name_en' => $data['name_en'],
                'name_jp' => $data['name_jp'],
                'telephone' => $data['telephone'],
                'fax' => $data['fax'],
                'company_name' => $data['company_name'],
                'tax_code' => $data['tax_code'],
                'hotel_code' => $data['hotel_code'],
                'email' => $data['email'],
                'address_1' => $data['address_1'],
                'address_2' => $data['address_2'],
            ]);

            return $newHotel;
        } catch(Exception $e) {
            throw new Exception('database has some errors!!');
        }
    }

    public function searchHotels(array $filters) {
        try {
            $query = $this->_model::query()->with('city'); // Khởi tạo query builder

            if (!empty($filters['city_id'])) {
                $query->where('city_id', $filters['city_id']);
            }
            if (!empty($filters['hotel_code'])) {
                $query->where('hotel_code', 'LIKE', "%{$filters['hotel_code']}%");
            }
            if (!empty($filters['name_en'])) {
                $query->where('name_en', 'LIKE', "%{$filters['name_en']}%");
            }
            if (!empty($filters['owner_id'])) {
                $query->where('owner_id', $filters['owner_id']);
            }

            return $query->paginate(Constant::PAGINATE_DEFAULT)->appends($filters); 
        } catch (Exception $e) {
            throw new Exception('Pagination or database has some errors!!');
        }
    }

    public function updateHotel(array $data, $hotel_id) 
    {
        try {
            // Lấy thông tin của hotel theo id
            $hotel = $this->_model::findOrFail($hotel_id);

            // Kiểm tra xem hotel có tồn tại không
            if (empty($hotel)) {
                throw new Exception('Hotel not found');
            }

            // Kiểm tra xem user có quyền truy cập hotel này không
            if ($hotel->owner_id != $data['owner_id']) {
                throw new Exception('You do not have permission to access this hotel');
            }

            // Cập nhật thông tin mới
            $hotel->update([
                'city_id' => $data['city_id'],
                'name_en' => $data['name_en'],
                'name_jp' => $data['name_jp'],
                'telephone' => $data['telephone'],
                'fax' => $data['fax'],
                'company_name' => $data['company_name'],
                'tax_code' => $data['tax_code'],
                'hotel_code' => $data['hotel_code'],
                'email' => $data['email'],
                'address_1' => $data['address_1'],
                'address_2' => $data['address_2'],
            ]);

            return $hotel;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('Hotel not found');
        } catch (\Illuminate\Database\QueryException $e) {
            throw new Exception('Database has some errors!!');
        } catch (Exception $e) {
            throw $e; // Giữ nguyên thông báo lỗi khi không có quyền truy cập
        }
    }

    public function deleteHotel($hotel_id, $owner_id) {
        try {
            // Lấy thông tin của hotel theo id
            $hotel = $this->_model::findOrFail($hotel_id);

            // Kiểm tra xem hotel có tồn tại không
            if (empty($hotel)) {
                throw new Exception('Hotel not found');
            }

            // Kiểm tra xem user có quyền truy cập hotel này không
            if ($hotel->owner_id != $owner_id) {
                throw new Exception('You do not have permission to access this hotel');
            }

            // Xóa hotel
            $hotel->delete();

            return true;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('Hotel not found');
        } catch (\Illuminate\Database\QueryException $e) {
            throw new Exception('Database has some errors!!');
        } catch (Exception $e) {
            throw $e; // Giữ nguyên thông báo lỗi khi không có quyền truy cập
        }
    }
}