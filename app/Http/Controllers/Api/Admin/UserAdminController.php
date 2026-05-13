<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUpdateUserRequest;
use App\Models\User;
use App\Services\Api\Admin\UserAdminService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    public function __construct(
        private UserAdminService $userService
    ) {}

    public function index(Request $request)
    {
        try {
            if (!$request->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 403);
            }

            $users = $this->userService->getUsersForAdmin([
                'search' => $request->query('search'),
                'role' => $request->query('role'),
                'per_page' => $request->query('per_page', 10),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Users fetched successfully',
                'data' => $users,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(AdminUpdateUserRequest $request, User $user)
    {
        try {
            $updatedUser = $this->userService->updateUserByAdmin(
                $user,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => $updatedUser,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}