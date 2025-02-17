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
                throw new Exception("the {$data['name']} role already exists in the database!!");
            }

            $newRole = NULL;

            try {
                $newRole = $this->_model::create([
                    'name' => $data['name'],
                    'description' => $data['description'],
                ]);

            } catch (Exception $e) {
                throw new Exception('input values or database has some values!!');
            }
            
            return $newRole;
        } catch (\Illuminate\Database\QueryException $e) {
            throw new Exception('Database has some errors!!');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function updateRole(array $data, $id) {
        try {
            $role = $this->_model::find($id);
            
            $role->name = $data['name'];
            $role->description = $data['description'];

            $role->save();
            return $role;
        } catch (\Illuminate\Database\QueryException $e) {
            throw new Exception('Database query error: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception(__('exceptions.database.update'));
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
        } catch (\Illuminate\Database\QueryException $e) {
            throw new Exception('Database query error: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('An unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function deleteRole($role_id, $auth_id) {
        try {
            $role = $this->_model::find($role_id);

            // Check if the role exists
            if (!$role) {
                throw \Illuminate\Database\Eloquent\ModelNotFoundException('Role not found');
            }
            
            // check if the user has permission to delete the role
            $auth = User::with('role')->find($auth_id);
            if (!$auth) {
                throw \Illuminate\Database\Eloquent\ModelNotFoundException('Errors in the auth user!!');
            }
            if ($auth->role->name !== Constant::ADMIN_ROLE_NAME) {
                throw new AuthorizationException('You do not have permission to delete this role!');
            }

            // check if exists user with role
            $users = User::where('role_id', $role->id)->get();
            if ($users->count() > 0) {
                throw new Exception('There are users with this role, you cannot delete this role!');
            }

            if ($auth->role->name !== Constant::ADMIN_ROLE_NAME) {
                throw new AuthorizationException('You do not have permission to delete this role!');
            }

            $role->delete();
            return $role;
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (\Illuminate\ModelNotFoundException $e) {
            throw $e;
        } catch (\Illuminate\Database\QueryException $e) {
            throw new Exception('Database query error: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('An unexpected error occurred: ' . $e->getMessage());
        }
    }
}