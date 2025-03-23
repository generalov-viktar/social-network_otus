<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Random\RandomException;

class UserController extends Controller
{
    public function register(
        RegisterRequest $request,
        UserRepositoryInterface $userRepository
    ): JsonResponse {
        $user = $userRepository->create(data: $request->validated());

        return response()->json([
            'user_id' => $user->id,
        ], 201);
    }

    /**
     * @throws RandomException
     */
    public function login(
        LoginRequest $request,
        UserRepositoryInterface $userRepository
    ): JsonResponse {
        $user = $userRepository->findById(id: $request->integer('id'));

        if (
            !$user
            || !Hash::check(value: $request->string('password')->toString(), hashedValue: $user->password)
        ) {
            return response()->json([
                'message' => 'Неверные учетные данные',
            ], 401);
        }

        $user->tokens()->delete();
        $token = $user->createToken('auth_token');

        return response()->json([
            'token' => $token->token,
        ]);
    }

    public function show(
        int $id,
        UserRepositoryInterface $userRepository
    ): JsonResponse {
        $token = request()->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Неавторизованный доступ'
            ], 401);
        }

        $user = $userRepository->findByToken($token);

        if (!$user) {
            return response()->json([
                'message' => 'Недействительный токен'
            ], 401);
        }

        $targetUser = $userRepository->findById($id);

        if (!$targetUser) {
            return response()->json([
                'message' => 'Пользователь не найден'
            ], 404);
        }

        return response()->json([
            'id' => $targetUser->id,
            'first_name' => $targetUser->first_name,
            'second_name' => $targetUser->second_name,
            'birthdate' => $targetUser->birthdate->format('Y-m-d'),
            'biography' => $targetUser->biography,
            'city' => $targetUser->city,
        ]);
    }
}
