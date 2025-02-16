<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Common\Constant;
use Exception;
use Illuminate\Http\Request;


/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API Documentation",
 *     description="Tài liệu API"
 * )
 */
class RoleController extends Controller
{
    protected RoleService $_roleService;

    public function __construct(RoleService $roleService)
    {
        // $this->middleware('auth');
        $this->_roleService = $roleService;
    }

    public function index(Request $request)
    {
        try {
            $roles = $this->_roleService->getAll(); // Gọi service để lấy danh sách roles
            
            if ($request->ajax()) {
                return response()->json([
                    'roles' => $roles,
                    'paginationHtml' => $roles->links('vendor.pagination.custom')->render(),
                ]);
            }

            return view('roles.index', compact('roles'));
        } catch (Exception $e) {
            Log::error($e->getMessage()); // Ghi log lỗi
            session()->flash('error', 'Something went wrong!');
            abort(404);
        }
    }


    public function createRole() {
        try {
            $roles_constant = Constant::getRoles();
            $roles_exist = $this->_roleService->getAll();

            // catch exception when data has no field 'data'
            try {
                $roles_existed_collection = collect(collect($roles_exist)['data']);
            } catch (Exception $e) {
                throw new Exception('data missing some fields to work!!!');
            }

            $roles = $roles_constant->map(function ($item) use ($roles_existed_collection) {
                $item['isExisted'] = $roles_existed_collection->contains('name', $item['name']);
                return (object) $item;
            });
            
            log::info('get create role page');
            return view('roles.create', ['roles' => $roles]);
            // return response()->json($roles);
        } catch (Exception $e) {
            log::error($e->getMessage());
            return view('home');
        }
    }

    public function editRole($id) {
        try {
            $role = $this->_roleService->findRoleById($id);
            log::info("edit role of id {$id}");
            return view('roles.edit', compact('role'));
        } catch (Exception $e) {
            log::error($e->getMessage());
            session()->flash('error', $e->getMessage());
            return view('home');
        }
    }
}