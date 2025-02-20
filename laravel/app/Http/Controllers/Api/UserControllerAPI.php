<?php

// namespace App\Http\Controllers\Api;

// use Illuminate\Support\Facades\Auth;
// use App\Common\StatusCode;
// use App\Models\User;
// use App\Http\Request\UserRequest;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;
// use App\Services\UserService;
// use Illuminate\Support\Facades\App;
// use Illuminate\Support\Facades\Log;
// use Exception;
// use App\Http\Controllers\Controller;

// class UserControllerAPI extends Controller
// {
//     protected UserService $_userService;

//     /**
//      * Create a new controller instance.
//      *
//      * @return void
//      */
//     public function __construct(UserService $userService)
//     {
//         // $this->middleware('auth');
//         $this->_userService = $userService;
//     }

//     public function store(UserRequest $request)
//     {   
//         try {
//             $newUser = $this->_userService->createUser($request->validated());
//             log::info("create new user with name = {$newUser->name}");
//             return response()
//                     ->json(['message' => __('messages.user.create.success'), 
//                             'user' => $newUser], StatusCode::HTTP_STATUS_CREATED);
//         } catch(Exception $e) {
//             log::error($e->getMessage());
//             return response()->json([
//                 'message' => $e->getMessage(),
//             ], StatusCode::HTTP_STATUS_INTERNAL_SERVER_ERROR);
//         }
//     }

//     public function update(UserRequest $request, $id)
//     {
//         try {
//             $auth_id = $request->input('auth_id'); // get auth_id from request
//             $user = $this->_userService->updateUser($request->validated(), $id, $auth_id);
//             return response()->json(['message' => __('messages.user.update.success'), 'user' => $user], StatusCode::HTTP_STATUS_ACCEPTED);
//         } catch (Exception $e) {
//             log::error($e->getMessage());
//             return response()->json([
//                 'message' => $e->getMessage(),
//             ], StatusCode::HTTP_STATUS_INTERNAL_SERVER_ERROR);
//         }
//     }

//     public function destroy(Request $request, $id)
//     {
//         try {
//             $auth_id = $request->input('auth_id');
//             if ($auth_id == $id) {
//                 throw new Exception(__('messages.user.delete.error'));
//             }
            
//             $user = $this->_userService->deleteUser($id);
//             return response()->json(['message' => __('messages.user.delete.success'), 'user' => $user], StatusCode::HTTP_STATUS_OK);
//         } catch (Exception $e) {
//             log::error($e->getMessage());
//             return response()->json([
//                 'message' => $e->getMessage(),
//             ], StatusCode::HTTP_STATUS_INTERNAL_SERVER_ERROR);
//         }
//     }

//     public function searchUsers(Request $request)
//     {
//         try {
//             $filters = $request->only(['keyword']);
            
//             // Perform the search using the UserService
//             $users = $this->_userService->searchUsers($filters);
            
//             log::info("search users with keyword " . json_encode($filters));
            
//             return response()->json([
//                 'users' => $users,
//                 'message' => 'Search users successfully!!',
//                 'paginationHtml' => $users->links('vendor.pagination.custom')->render(), // Send pagination HTML
//             ], StatusCode::HTTP_STATUS_OK);
//         } catch (Exception $e) {
//             log::error($e->getMessage());
//             return response()->json(['message' => $e->getMessage()], StatusCode::HTTP_STATUS_INTERNAL_SERVER_ERROR);
//         }
//     }
// }