<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Common\Constant;
use App\Http\Request\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\UserService;
use App\Services\RoleService;
use App\Common\StatusCode;
use Illuminate\Auth\Access\AuthorizationException;
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
        $this->_userService = $userService;
        $this->_roleService = $roleService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['keyword']);
            $users = $this->_userService->searchUsers($filters);
            return view('users.index', compact('users'));
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

    public function viewUserProfileById()
    {
        try {
            $id = Auth::user()->id;
            $user = $this->_userService->getUserProfileById($id);
            $roles = $this->_roleService->getAll();
            Log::info("get info of user id = {$id}");
            return view('users.profile', compact('user', 'roles'));
        } catch (AuthorizationException $e) {
            Log::error($e->getMessage());
            return view('error.default', ['status' => StatusCode::HTTP_STATUS_FORBIDDEN, 'message' => $e->getMessage()]); // Use StatusCode::HTTP_STATUS_FORBIDDEN
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return view('error.default', ['status' => StatusCode::HTTP_STATUS_NOT_FOUND, 'message' => $e->getMessage()]); // Use StatusCode::HTTP_STATUS_NOT_FOUND
        }
    }

    public function editUserProfileById($id)
    {
        try {
            $user = $this->_userService->getUserProfileById($id);
            $roles = $this->_roleService->getAll();
            Log::info("get info of user id = {$id}");
            return view('users.profile', compact('user', 'roles'));
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

    public function editUserProfileByIdAPI(UserRequest $request, $id)
    {
        try {
            $user = $this->_userService->updateUser($request->validated(), $id);
            log::info("update user successfully {$id}");
            return redirect()->back()->with('success', __('messages.user.update.success'));
        } catch (Exception $e) {
            log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function createUser() 
    {
        try {
            
            Log::info("get ui of create user");
            $roles = $this->_roleService->getAll();
            return view('users.create', compact('roles'));
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

    public function createUserAPI(UserRequest $request)
    {
        try {
            $newUser = $this->_userService->createUser($request->validated());
            log::info("create new user with name = {$newUser->name}");
            return redirect()->to('/users')->with('success', __('messages.user.create.success'));
        } catch(Exception $e) {
            log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function deleteAPI(Request $request, $id)
    {
        try {
            $user = $this->_userService->deleteUser($id);
            return redirect()->back()->with('success', __('messages.user.delete.success'));
        } catch (Exception $e) {
            log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}