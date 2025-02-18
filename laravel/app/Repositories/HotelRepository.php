<?php 

namespace App\Repositories;
use App\Repositories\Interface\HotelRepositoryInterface;
use App\Http\Request\HotelRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Common\Constant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
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
            $hotels = $this->_model::with(['user', 'city'])->where('owner_id', $owner_id)->orderby('name_en', 'asc')->paginate(Constant::PAGINATE_DEFAULT);
            return $hotels;
        } catch(Exception $e) {
            throw new Exception('database has some errors!!');
        }
    }

    public function findHotelById($hotel_id, $owner_id) {
        try {
            // Lấy thông tin của hotel theo id
            $hotel = $this->_model::with('city')->findOrFail($hotel_id);

            // Kiểm tra xem user có quyền truy cập hotel này không
            if ($hotel->owner_id != $owner_id) {
                throw new AuthorizationException("You don't have permissions to do this actions!!");
            }

            return $hotel;
        } catch (AuthorizationException $e) {
            throw $e;
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
        } catch (AuthorizationException $e) {
            throw $e;
        } catch(Exception $e) {
            throw new Exception('database has some errors!!');
        }
    }

    public function searchHotels(array $filters) {
        try {
            $query = $this->_model::query()->with('city'); // Khởi tạo query builder
            if (empty($filters['owner_id'])) {
                throw new Exception('Owner id is required!!');
            }
            $owner = User::with('role')->findOrFail($filters['owner_id']);
            
            // Nếu owner không phải là admin thì chỉ hiển thị hotel của owner đó
            // nếu owner là admin thì hiển thị tất cả hotel
            if ($owner->role->name !== Constant::ADMIN_ROLE_NAME) {
                $query->where('owner_id', $filters['owner_id']);
            }

            // Lọc theo city_id, hotel_code, name_en
            if (!empty($filters['city_id'])) {
                $query->where('city_id', $filters['city_id']);
            }
            if (!empty($filters['hotel_code'])) {
                $query->where('hotel_code', 'LIKE', "%{$filters['hotel_code']}%");
            }
            if (!empty($filters['name_en'])) {
                $query->where('name_en', 'LIKE', "%{$filters['name_en']}%");
            }

            // Sắp xếp theo name_en
            $query->orderBy('name_en', 'asc');

            // Sắp xếp theo id
            return $query->paginate(Constant::PAGINATE_DEFAULT)->appends($filters); 
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('Owner not found');
        } catch (\Illuminate\Database\QueryException $e) {
            throw new Exception('Database has some errors!!');
        } catch (Exception $e) {
            throw $e;
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
                throw new AuthorizationException("You don't have permission to do this action!!");
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
        } catch (AuthorizationException $e) {
            throw $e;
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

            // Lấy thông tin của owner
            $owner= User::with('role')->findOrFail($owner_id);

            // nếu owner không phải là admin
            if ($owner->role->name !== Role::ADMIN_ROLE_NAME) {
                // Kiểm tra xem user có quyền truy cập hotel này không
                if ($hotel->owner_id != $owner_id) {
                    throw new AuthorizationException('You do not have permission to access this hotel');
                }            
            }

            // Xóa hotel
            $hotel->delete();

            return true;
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('Hotel not found');
        } catch (\Illuminate\Database\QueryException $e) {
            throw new Exception('Database has some errors!!');
        } catch (Exception $e) {
            throw $e; // Giữ nguyên thông báo lỗi khi không có quyền truy cập
        }
    }
}