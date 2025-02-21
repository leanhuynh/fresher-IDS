<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Services\RoleService;
use App\Common\StatusCode;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Common\Constant;
use App\Http\Request\RoleRequest;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected RoleService $_roleService;

    public function __construct(RoleService $roleService)
    {
        $this->_roleService = $roleService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['keyword']);
            $roles = $this->_roleService->searchRoles($filters);
            return view('roles.index', compact('roles'));
        } catch (AuthorizationException $e) {
            Log::error($e->getMessage()); // Ghi log lỗi
            session()->flash('error', $e->getMessage());
            return view('error.default', ['status' => StatusCode::HTTP_STATUS_FORBIDDEN, 'message' => $e->getMessage()]); // Use StatusCode::HTTP_STATUS_FORBIDDEN
        } catch (Exception $e) {
            Log::error($e->getMessage()); // Ghi log lỗi
            session()->flash('error', $e->getMessage());
            return view('error.default', ['status' => StatusCode::HTTP_STATUS_NOT_FOUND, 'message' => $e->getMessage()]); // Use StatusCode::HTTP_STATUS_NOT_FOUND
        }
    }

    public function createRole()
    {
        try {

            $roles_constant = Constant::getRoles();
            $roles_exist = $this->_roleService->getAll();

            // catch exception when data has no field 'data'
            try {
                $roles_existed_collection = collect(collect($roles_exist)['data']);
            } catch (Exception $e) {
                throw new Exception('Data is missing some fields to work!');
            }

            $roles = $roles_constant->map(function ($item) use ($roles_existed_collection) {
                $item['isExisted'] = $roles_existed_collection->contains('name', $item['name']);
                return (object) $item;
            });
            
            Log::info('get create role page');
            return view('roles.create', ['roles' => $roles]);
        } catch (AuthorizationException $e) {
            Log::error($e->getMessage());
            session()->flash('error', $e->getMessage());
            return view('error.default', ['status' => StatusCode::HTTP_STATUS_FORBIDDEN, 'message' => $e->getMessage()]); // Use StatusCode::HTTP_STATUS_FORBIDDEN
        } catch (Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', $e->getMessage());
            return view('error.default', ['status' => StatusCode::HTTP_STATUS_NOT_FOUND, 'message' => $e->getMessage()]); // Use StatusCode::HTTP_STATUS_NOT_FOUND
        }
    }

    public function createRoleAPI(RoleRequest $request) 
    {
        try {
            $role = $this->_roleService->createRole($request->validated());
            log::info("create role with name: {$role->name}");
            return redirect()->to('/roles')->with('success', __('messages.role.create.success'));
        } catch (Exception $e) {
            log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function editRole($id)
    {
        try {

            $role = $this->_roleService->findRoleById($id);
            Log::info("edit role of id {$id}");
            return view('roles.edit', compact('role'));
        } catch (AuthorizationException $e) {
            Log::error($e->getMessage());
            session()->flash('error', $e->getMessage());
            return view('error.default', ['status' => StatusCode::HTTP_STATUS_FORBIDDEN, 'message' => $e->getMessage()]); // Use StatusCode::HTTP_STATUS_FORBIDDEN
        } catch (Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', $e->getMessage());
            return view('error.default', ['status' => StatusCode::HTTP_STATUS_NOT_FOUND, 'message' => $e->getMessage()]); // Use StatusCode::HTTP_STATUS_NOT_FOUND
        }
    }

    public function editRoleAPI(RoleRequest $request, $id) 
    {
        try {
            $role = $this->_roleService->updateRole($request->validated(), $id);
            log::info("update of role id {$id}");
            return redirect()->back()->with('success', __('messages.role.update.success'));
        } catch (Exception $e) {
            log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function deleteAPI(Request $request, $role_id)
    {
        try {
            $auth_id = Auth::user()->id;
            $this->_roleService->deleteRole($role_id, $auth_id);
            log::info("delete role with id: {$role_id} and auth_id: {$auth_id}");
            return redirect()->back()->with('success', __('messages.role.delete.success'));
        } catch (Exception $e) {
            log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}