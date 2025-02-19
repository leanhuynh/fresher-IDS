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
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use \Illuminate\Database\QueryException;
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
            $owner = User::with('role')->find($owner_id);
            if (empty($owner)) {
                throw new ModuleNotFoundException(__('exceptions.not_found.owner'));
            }

            // thực thi câu truy vấn
            $query = $this->_model::query()->with(['user', 'city']);

            // nếu role của người dùng hiện tại không phải là Admin
            if ($owner->role->name !== Constant::ADMIN_ROLE_NAME) {
                $query->where('owner_id', $owner_id);
            }

            $hotels = $query->orderby('name_en', 'asc')->paginate(Constant::PAGINATE_DEFAULT);
            return $hotels;
        } catch (ModuleNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new QueryException(__('exceptions.database.error'));
        } catch(Exception $e) {
            throw new Exception(__('exceptions.unknown'));
        }
    }

    public function findHotelById($hotel_id, $owner_id) {
        try {
            $owner = User::with('role')->find($owner_id);
            if (empty($owner)) {
                throw new ModelNotFoundException(__('exceptions.not_found.author'));
            }

            // Lấy thông tin của hotel theo id
            $hotel = $this->_model::with('city')->findOrFail($hotel_id);
            if (empty($hotel)) {
                throw new ModelNotFoundException(__('exceptions.not_found.hotel'));
            }

            // Kiểm tra xem user có quyền truy cập hotel này không
            if ($owner->role->name !== Constant::ADMIN_ROLE_NAME && 
                    $hotel->owner_id != $owner_id) {
                throw new AuthorizationException(__('exceptions.permission.action.view.hotel'));
            }

            return $hotel;
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new QueryException(__('exceptions.database.error'));
        } catch (Exception $e) {
            throw new Exception(__('exceptions.unknown'));
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
        } catch (QueryException $e) {
            throw new QueryException(__('exceptions.database.error'));
        } catch(Exception $e) {
            throw new Exception(__('exceptions.unknown'));
        }
    }

    public function searchHotels(array $filters) {
        try {
            $query = $this->_model::query()->with('city'); // Khởi tạo query builder

            if (empty($filters['owner_id'])) {
                throw new ModelNotFoundException(__('exceptions.not_found.owner'));
            }
            $owner = User::with('role')->findOrFail($filters['owner_id']);
            if (empty($owner)) {
                throw new ModelNotFoundException(__('exceptions.not_found.owner'));
            }
            
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
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new Exception(__('exceptions.database.error'));
        } catch (Exception $e) {
            throw new Exception(__('exceptions.unknown'));
        }
    }

    public function updateHotel(array $data, $hotel_id) 
    {
        try {
            // Lấy thông tin của hotel theo id
            $hotel = $this->_model::findOrFail($hotel_id);
            $owner_id = $data['owner_id'];
            $owner = User::with('role')->find($owner_id);

            // Kiểm tra xem hotel có tồn tại không
            if (empty($hotel)) {
                throw new ModelNotFoundException(__('exceptions.not_found.hotel'));
            }

            // Kiểm tra xem user có quyền truy cập hotel này không
            if ($owner->role->name !== Constant::ADMIN_ROLE_NAME && $hotel->owner_id != $data['owner_id']) {
                throw new AuthorizationException(__('exceptions.permission.action.edit.hotel'));
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
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new QueryException(__('exceptions.database.error'));
        } catch (Exception $e) {
            throw new Exception(__('exceptions.unknown'));
        }
    }

    public function deleteHotel($hotel_id, $owner_id) {
        try {
            // Lấy thông tin của hotel theo id
            $hotel = $this->_model::findOrFail($hotel_id);
            // Kiểm tra xem hotel có tồn tại không
            if (empty($hotel)) {
                throw new Exception(__('exceptions.not_found.hotel'));
            }

            // Lấy thông tin của owner
            $owner= User::with('role')->findOrFail($owner_id);
            if (empty($owner)) {
                throw new ModuleNotFoundException(__('exceptions.not_found.owner'));
            }

            // nếu owner không phải là admin
            if ($owner->role->name !== Role::ADMIN_ROLE_NAME) {
                // Kiểm tra xem user có quyền truy cập hotel này không
                if ($hotel->owner_id != $owner_id) {
                    throw new AuthorizationException(__('exceptions.permission.action.delete.hotel'));
                }            
            }

            // Xóa hotel
            $hotel->delete();

            return true;
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new Exception(__('exceptions.database.error'));
        } catch (Exception $e) {
            throw new Exception(__('exceptions.unknown'));
        }
    }
}