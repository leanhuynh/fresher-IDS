<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Request\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\UserService;
use App\Services\RoleService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Exception;

class UserController extends Controller
{
    protected UserService $_userService;

    public function __construct(
        UserService $userService,
        RoleService $roleService)
    {
        // $this->middleware('auth');
        $this->_userService = $userService;
        $this->_roleService = $roleService;
    }

    public function index(Request $request)
    {
        try {
            $users = $this->_userService->getAll();

            if ($request->ajax()) {
                return response()->json([
                    'users' => $users,
                    'paginationHtml' => $users->links('vendor.pagination.custom')->render(),
                ]);
            }
            return view('users.index', compact('users'));
        } catch (Exception $e) {
            log::error($e->getMessage());
            session()->flash('error', $e->getMessage());
            abort(404);
        }
    }

    public function viewUserProfileById()
    {
        try {
            $id = Auth::user()->id;
            $user = $this->_userService->getUserProfileById($id);
            $roles = $this->_roleService->getAll();
            log::info("get infor of user id = {$id}");
            return view('users.profile', compact('user', 'roles'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return view('home');
        }
    }

    public function editUserProfileById($id)
    {
        try {
            $user = $this->_userService->getUserProfileById($id);
            $roles = $this->_roleService->getAll();
            log::info("get infor of user id = {$id}");
            return view('users.profile', compact('user', 'roles'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return view('home');
        }
    }

    public function createUser() 
    {
        try {
            log::info("get ui of create user");
            $roles = $this->_roleService->getAll();
            return view('users.create', compact('roles'));
        } catch (Exception $e) {
            log::error($e->getMessage());
            return view('home');
        }
    }
}