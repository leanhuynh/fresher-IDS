<?php 
namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
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
use Illuminate\Validation\ValidationException;
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
            if (empty($user)) {
                throw new ModuleNotFoundException(__('exceptions.not_found.user'));
            }
            return $user;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new QueryException(__('exceptions.database.error'));
        } catch (Exception $e) {
            throw new Exception(__('exceptions.unknown'));
        }
    }

    public function createUser(array $data) {
        try {
            $auth_id = Auth::user()->id;
            // nếu data không có key 'auth_id' hoặc là field auth_id rỗng
            if (empty($auth_id)) {
                throw ValidationException::withMessages(['message' => __('exceptions.not_found.auth')]);
            }

            // tìm kiếm thông tin author
            $auth = $this->_model::with('role')->findorFail($auth_id);
            if ($auth->role->name !== Constant::ADMIN_ROLE_NAME) {
                throw new AuthorizationException(__('exceptions.permission.action.create.user'));
            }

            // nếu data không có key 'password' hoặc là field password rỗng
            if (!isset($data['password']) || empty($data['password'])) {
                throw new ValidationException(__('exceptions.not_found.password'));
            }

            // save avatar
            $imagePath = "";
            if (isset($data['avatar']) && !empty($data['avatar'])) {
                $image = $data['avatar'];
                $imagePath = $image->store('/images', 'public');
            }

            $admin = Role::where('name', Constant::ADMIN_ROLE_NAME)->first();
            if (empty($admin)) {
                throw new ModuleNotFoundException(__('exceptions.not_found.admin'));
            }
            // set role id
            $role_id = (isset($data['role_id']) && !empty($data['role_id'])) 
                ? $data['role_id'] 
                : $admin->id;
                
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
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (ValidationException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new QueryException(__('exceptions.database.error'), $e->getBindings(), $e);
        } catch (Exception $e) {
            // throw new Exception(__('exceptions.unknown'));
            throw new Exception($e->getMessage());
        }
    }

    public function updateUser(array $data, $id) {
        try {
            $auth_id = Auth::user()->id;
            $user = $this->_model::with('role')->findorFail($id); // find user by id or throw exception
            $auth = $this->_model::with('role')->findorFail($auth_id); // find user by id or throw exception
            $admin = Role::where('name', Constant::ADMIN_ROLE_NAME)->first();

            // check empty
            if (empty($user)) {
                throw new ModuleNotFoundException(__('exceptions.not_found.auth'));
            }
            if (empty($auth)) {
                throw new ModuleNotFoundException(__('exceptions.not_found.auth'));
            }
            if (empty($admin)) {
                throw new ModuleNotFoundException(__('exceptions.not_found.admin'));
            }

            // init role_id value of user
            $role_id = $user->role_id;
            // check permission of actor
            if (isset($data['role_id']) && !empty($data['role_id'])) {

                // trường hợp user đang login là không phải Admin
                if ($auth->role->name !== Constant::ADMIN_ROLE_NAME) {
                    // Members thay đổi thông tin của tài khoản khác
                    if ($auth_id !== $id) {
                        throw new AuthorizationException(
                        __('exceptions.permission.action.edit.role')); 
                    } else if ($data['role_id'] != $user->role->id) {
                        // Members thay đổi thông tin role của bản thân mình
                        throw new AuthorizationException(
                        __('exceptions.permission.action.edit.role'));
                    }
                } 
                // else {
                //     // nếu có sự thay đổi role và user cần thay đổi là admin
                //     if ($data['role_id'] != $user->role->id 
                //             && $user->role->name === Constant::ADMIN_ROLE_NAME) {
                //         throw new AuthorizationException(
                //             __('exceptions.permission.action.edit.role'));
                //     }
                // }

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
            // if ($data['password']) {
            //     $user->password = Hash::make($data['password']);
            // }
            $user->save();

            return $user;
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new QueryException(__('exceptions.database.error'), $e->getBindings(), $e);
        } catch (Exception $e) {
            // throw new Exception(__('exceptions.unknown'));
            throw new Exception($e->getMessage());
        }
    }

    public function deleteUser($id) {
        try {
            // check exist user
            $user = $this->_model::find($id);
            if (empty($user)) {
                throw new ModelNotFoundException(__('exceptions.not_found.user'));
            }

            // check exist hotel with owner_id is user_id
            $hotels = Hotel::where('owner_id', $id)->get();
            if ($hotels->count() > 0) {
                throw new AuthorizationException(__('exceptions.exist.hotel'));
            }

            $user->delete();
            return true;
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new QueryException(__('exceptions.database.error'), $e->getBindings(), $e);
        } catch (Exception $e) {
            throw new Exception(__('exceptions.unknown'));
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
            throw new QueryException(__('exceptions.database.error'), $e->getBindings(), $e);
        } catch (Exception $e) {
            throw new Exception(__('exceptions.unknown'));
        }
    }

    public function getUserProfileById($id) 
    {
        try {
            $user = $this->_model::with('role')->where('id', $id)->first();

            if (empty($user)) {
                throw new ModelNotFoundException(__('exceptions.not_found.user'));
            }

            return $user;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new QueryException(__('exceptions.database.error'), $e->getBindings(), $e);
        } catch (Exception $e) {
            throw new Exception(__('exceptions.unknown'));
        }
    }
}