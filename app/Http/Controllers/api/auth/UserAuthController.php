<?php

namespace App\Http\Controllers\api\auth;

use App\Constants\ApiStatus;
use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserAuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->failedResponse('Validation failed', $validator->errors(), ApiStatus::HTTP_422);
        }

        $credentials = $request->only('username', 'password');

        try {
            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return $this->errorResponse('Authentication failed. Please check your username and password.', [], ApiStatus::HTTP_401);
            }
        } catch (JWTException $e) {
            return $this->errorResponse('Could not create token', ['exception' => $e->getMessage()], ApiStatus::HTTP_500);
        }

        return $this->successResponse([
            'token'      => $token,
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'admin'      => Auth::guard('api')->user(),
        ], 'Login successful', ApiStatus::HTTP_200);
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return $this->errorResponse(
                    'Token not provided',
                    [],
                    ApiStatus::HTTP_400
                );
            }

            Auth::guard('api')->invalidate($token);
        } catch (JWTException $e) {
            return $this->errorResponse(
                'Failed to logout, please try again',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_500
            );
        }

        return $this->successResponse([], 'Successfully logged out', ApiStatus::HTTP_200);
    }

    public function updateUser(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            $user->update($request->only(['name', 'email']));
            return response()->json($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to update user'], 500);
        }
    }

    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return $this->errorResponse(
                    'User not found',
                    [],
                    ApiStatus::HTTP_404
                );
            }

            return $this->successResponse(
                $user,
                'User retrieved successfully',
                ApiStatus::HTTP_200
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to fetch user',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_401
            );
        }
    }

    public function refresh()
    {
        try {
            $newToken = Auth::guard('api')->refresh();

            return $this->successResponse([
                'token'      => $newToken,
                'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            ], 'Token refreshed successfully', ApiStatus::HTTP_200);
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to refresh token',
                ['exception' => $e->getMessage()],
                ApiStatus::HTTP_401
            );
        }
    }
}
