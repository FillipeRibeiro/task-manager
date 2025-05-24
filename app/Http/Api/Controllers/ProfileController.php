<?php

namespace App\Http\Api\Controllers;

use App\Http\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Profile",
 *     description="Endpoints for managing the authenticated user's profile"
 * )
 */
class ProfileController extends Controller
{
    /**
     * @OA\Put(
     *     path="/api/profile",
     *     summary="Update authenticated user's profile",
     *     tags={"Profile"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProfileUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     )
     * )
     */
    public function update(ProfileUpdateRequest $request): UserResource
    {
        $user = User::where('id', auth()->id())
            ->update($request->validated());

        return new UserResource($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/profile",
     *     summary="Delete authenticated user's account",
     *     tags={"Profile"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="password", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User account deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User Deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed"
     *     )
     * )
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'User Deleted'
        ], Response::HTTP_OK);
    }
}
