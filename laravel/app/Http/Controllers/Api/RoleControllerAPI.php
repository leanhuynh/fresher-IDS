<?php

namespace App\Http\Controllers\Api;

use App\Common\StatusCode;
use App\Models\User;
use App\Http\Request\RoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Common\Constant;
use Exception;

class RoleControllerAPI extends Controller
{
    protected RoleService $_roleService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RoleService $roleService, UserService $userService)
    {
        // $this->middleware('auth');
        $this->_roleService = $roleService;
        $this->_userService = $userService;
    }

    public function getAll() 
    {
        try {
            $roles = $this->_roleService->getAll();
            log::info('get info of all roles');
            return response()->json(['roles' => $roles], StatusCode::HTTP_STATUS_OK);
        } catch (Exception $e) {
            log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], StatusCode::HTTP_STATUS_INTERNAL_SERVER_ERROR);
        }
        
    }

    public function update(RoleRequest $request, $id) 
    {
        try {
            $role = $this->_roleService->updateRole($request->validated(), $id);
            log::info("update of role id {$id}");
            return response()->json(['role' => $role, 'message' => 'Update role successfully!'], StatusCode::HTTP_STATUS_OK);
        } catch (Exception $e) {
            log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], StatusCode::HTTP_STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(RoleRequest $request) 
    {
        try {
            $role = $this->_roleService->createRole($request->validated());
            log::info("create role with name: {$role->name}");
            return response()->json(['role' => $role, 'message' => 'Create role successfully!'], StatusCode::HTTP_STATUS_CREATED);
        } catch (Exception $e) {
            log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], StatusCode::HTTP_STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    public function searchRoles(Request $request)
    {
        try {
            $filters = $request->only(['keyword']); // Lấy từ khóa tìm kiếm
            
            // Gọi service để tìm kiếm Roles
            $roles = $this->_roleService->searchRoles($filters);
            
            Log::info("Search roles with keyword: " . json_encode($filters));
            
            return response()->json([
                'roles' => $roles,
                'message' => 'Search roles successfully!!',
                'paginationHtml' => $roles->links('vendor.pagination.custom')->render(), // Render HTML phân trang
            ], StatusCode::HTTP_STATUS_OK);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Something went wrong!'], StatusCode::HTTP_STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request, $role_id) 
    {
        try {
            $auth_id = $request->input('auth_id');
            $this->_roleService->deleteRole($role_id, $auth_id);
            log::info("delete role with id: {$role_id} and auth_id: {$auth_id}");
            return response()->json(['message' => 'Delete role successfully!'], StatusCode::HTTP_STATUS_OK);
        } catch (Exception $e) {
            log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], StatusCode::HTTP_STATUS_INTERNAL_SERVER_ERROR);
        }
    }
}