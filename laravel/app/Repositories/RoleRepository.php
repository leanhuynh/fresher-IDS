<?php 

namespace App\Repositories;
use App\Repositories\Interface\RoleRepositoryInterface;
use App\Http\Request\RoleRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Common\Constant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\ModelNotFoundException;
use App\Exceptions\RoleInUseException;
use Exception;

class RoleRepository implements RoleRepositoryInterface 
{
    protected $_model;

    // constructor
    public function __construct() {
        $this->_model = app()->make(\App\Models\Role::class);
    }

    // implement interface function
    public function getAll() {
        $roles = $this->_model::simplePaginate(Constant::PAGINATE_DEFAULT);
        return $roles;
    }

    public function findRoleById($id) {
        $role = $this->_model::find($id);
        return $role;
    }

    public function createRole(array $data) {
        try {
            $role = $this->_model::find($data['name']);
            if ($role) {
                throw new ValidationException(__('exceptions.exist.role'));
            }

            $newRole = $this->_model::create([
                'name' => $data['name'],
                'description' => $data['description'],
            ]);
            
            return $newRole;
        } catch (ValidationException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new QueryException(__('exceptions.database.error'));
        } catch (Exception $e) {
            throw new Exception(__('exceptions.unknown'));
        }
    }

    public function updateRole(array $data, $id) {
        try {
            $role = $this->_model::find($id);
            
            $role->name = $data['name'];
            $role->description = $data['description'];

            $role->save();
            return $role;
        } catch (QueryException $e) {
            throw new QueryException(__('exceptions.database.error'));
        } catch (Exception $e) {
            throw new Exception(__('exceptions.unknown'));
        }
    }

    public function searchRoles(array $filters) {
        try {
            $query = $this->_model::query();

            if (!empty($filters['keyword'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('name', 'LIKE', "%{$filters['keyword']}%");
                });
            }

            $roles = $query->paginate(Constant::PAGINATE_DEFAULT)->appends($filters);

            return $roles;  
        } catch (QueryException $e) {
            throw new QueryException(__('exceptions.database.error'));
        } catch (Exception $e) {
            throw new Exception(__('exceptions.unknown'));
        }
    }

    public function deleteRole($role_id, $auth_id) {
        try {
            $role = $this->_model::find($role_id);

            // Check if the role exists
            if (!$role) {
                throw new ModelNotFoundException(__('exceptions.not_found.role'));
            }
            
            // check if the user has permission to delete the role
            $auth = User::with('role')->find($auth_id);
            if (empty($auth)) {
                throw new ModelNotFoundException(__('exceptions.not_found.auth'));
            }
            if ($auth->role->name !== Constant::ADMIN_ROLE_NAME) {
                throw new AuthorizationException(__('exceptions.permission.action.delete.user'));
            }

            // check if exists user with role
            $users = User::where('role_id', $role->id)->get();
            if ($users->count() > 0) {
                throw new RoleInUseException();
            }

            if ($auth->role->name !== Constant::ADMIN_ROLE_NAME) {
                throw new AuthorizationException(__('exceptions.permission.action.delete.role'));
            }

            $role->delete();
            return $role;
        } catch (RoleInUseException $e) {
            throw $e;
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
}