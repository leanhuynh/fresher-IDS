<?php 
namespace App\Repositories;
use App\Repositories\Interface\UserRepositoryInterface;
use App\Http\Request\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Common\Constant;
use App\Models\Role;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\Access\AuthorizationException;
use Exception;

class UserRepository implements UserRepositoryInterface 
{
    protected $_model;

    // constructor
    public function __construct() {
        $this->_model = app()->make(\App\Models\User::class);
    }

    // implement interface function
    public function getAll() {
        $users = $this->_model::with('role')->orderBy('user_name', 'asc')->paginate(Constant::PAGINATE_DEFAULT);
        return $users;
    }

    public function findUserById($id) {
        try {
            $user = $this->_model::with('role')->findorFail($id);
            return $user;
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("User not found with ID: " . $id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function createUser(array $data) {
        try {
            // check password
            if (isset($data['password']) || empty($data['password'])) {
                throw new Exception('The password field is required.');
            }

            // save avatar
            $imagePath = "";
            if (isset($data['avatar']) && !empty($data['avatar'])) {
                $image = $data['avatar'];
                $imagePath = $image->store('/images', 'public');
            }

            // set role id
            $role_id = (isset($data['role_id']) && !empty($data['role_id'])) 
                ? $data['role_id'] 
                : Constant::DEFAULT_USER_ROLE;
                
            $newUser = $this->_model::create([
                'avatar' => $imagePath,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'user_name' => $data['user_name'],
                'role_id' => $role_id,
                'email' => $data['email'],
                'address' => $data['address'],
                'password' => Hash::make($data['password']), // mã hóa mật khẩu
            ]);

            return $newUser;
        } catch (QueryException $e) {
            throw new Exception("Database query error: " . $e->getMessage());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateUser(array $data, $id, $auth_id) {
        try {
            // check permission of user
            $user = $this->_model::with('role')->findorFail($id); // find user by id or throw exception
            $auth = $this->_model::with('role')->findorFail($auth_id); // find user by id or throw exception
            
            // get role id of change user
            $role_id = $user->role_id;

            // check permission of actor
            if (isset($data['role_id']) && !empty($data['role_id'])) {

                // trường hợp user đang login là members
                // và actor muốn thay đổi profile của người khác
                if ($auth->role->name !== Constant::ADMIN_ROLE_NAME && $auth->id !== $user->id) {
                    throw new AuthorizationException(__('exceptions.permission.role'));
                } else {
                    // nếu có sự thay đổi role và user cần thay đổi là admin
                    if ($data['role_id'] != $user->role->id && $user->role->name === Constant::ADMIN_ROLE_NAME) {
                        throw new AuthorizationException(__('exceptions.permission.role'));
                    }
                }

                // set role id
                $role_id = $data['role_id'];
            }

            // save avatar
            $imagePath = $user->avatar;
            if (isset($data['avatar']) && $data['avatar']) {
                $image = $data['avatar'];
                $imagePath = $image->store('/images', 'public');
            }

            // update user
            $user->avatar = $imagePath;
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->user_name = $data['user_name'];
            $user->role_id = $role_id;
            $user->email = $data['email'];
            $user->address = $data['address'];
            $user->role_id = $role_id;

            // encode the password
            if ($data['password']) {
                $user->password = Hash::make($data['password']);
            }
            $user->save();

            return $user;
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (ModelNotFoundException $e) {
            throw new Exception('User not found with ID: ' . $id);
        } catch (QueryException $e) {
            throw new Exception("Database query error: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("An unexpected error occurred: " . $e->getMessage());
        }
    }

    public function deleteUser($id) {
        try {
            // check exist user
            $user = $this->_model::find($id);
            if (!$user) {
                throw new ModelNotFoundException("User with ID {$id} not found.");
            }

            // check exist hotel with owner_id is user_id
            $hotels = Hotel::where('owner_id', $id)->get();
            if ($hotels->count() > 0) {
                throw new AuthorizationException(__('exceptions.user.delete.hotel'));
            }

            $user->delete();
            return true;
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException($e->getMessage());
        } catch (QueryException $e) {
            throw new Exception("Database query error: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("An unexpected error occurred: " . $e->getMessage());
        }
    }

    public function searchUsers(array $filters)
    {
        try {
            $query = $this->_model::query()->with('role');

            if (!empty($filters['keyword'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('user_name', 'LIKE', "%{$filters['keyword']}%")
                    ->orWhere('email', 'LIKE', "%{$filters['keyword']}%");
                });
            }
            $query->orderBy('user_name', 'asc');
            $users = $query->paginate(Constant::PAGINATE_DEFAULT)->appends($filters);

            return $users;
        } catch (QueryException $e) {
            throw new Exception('Database query error: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('An unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function getUserProfileById($id) 
    {
        try {
            $user = $this->_model::with('role')->where('id', $id)->first();

            if (!$user) {
                throw new ModelNotFoundException("User with ID {$id} not found.");
            }

            return $user;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new Exception("Database query error: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("An unexpected error occurred: " . $e->getMessage());
        }
    }
}