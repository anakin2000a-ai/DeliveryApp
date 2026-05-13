<?php

namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;

use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Services\Api\User\UserService;
use Exception;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function register(RegisterUserRequest $request)
    {
        try {
            $user = $this->userService->register($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'user' => $user,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $user = $this->userService->updateProfile(
                $request->user(),
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => $user,
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