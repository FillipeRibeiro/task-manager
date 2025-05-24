<?php

namespace App\Http\Api\Controllers;

use App\Http\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User Login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authorization token generated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|abcd1234token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Wrong credentials"
     *     )
     * )
     */
    public function login (LoginRequest $request): JsonResponse
    {
        $userData = $request->validated();

        $user = User::where('email', $userData['email'])->first();

        if (! $user || ! Hash::check($userData['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="New user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterUserRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User saved",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Leonard Green"),
     *                 @OA\Property(property="email", type="string", example="leonard@email.com")
     *             ),
     *             @OA\Property(property="token", type="string", example="1|abcd1234token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid data"
     *     )
     * )
     */
    public function register (RegisterUserRequest $request): JsonResponse
    {
        $userData = $request->validated();

        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
        ]);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('api-token')->plainTextToken,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/refresh-token",
     *     summary="Refresh token",
     *     tags={"Authentication"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="New token generated",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|newtoken123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Not authenticated"
     *     )
     * )
     */
    public function refreshToken(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="User logout",
     *     tags={"Authentication"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Not authenticated"
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
