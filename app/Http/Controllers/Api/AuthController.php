<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'cashier',
        ]);

        return response()->json([
            'status_code' => 201,
            'success' => true,
            'message' => 'User registered successfully',
            'data' => $user,
        ], 201);
    }

    /**
     * Login user and return JWT token.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        // Attempt to authenticate and get JWT token
        $token = Auth::guard('api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'status_code' => 401,
                'success' => false,
                'message' => 'Email atau password salah',
                'data' => null,
            ], 401);
        }

        return response()->json([
            'status_code' => 200,
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => Auth::guard('api')->user(),
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
            ],
        ]);
    }

    /**
     * Get authenticated user.
     */
    public function me(Request $request)
    {
        try {
            $user = $this->jwt->parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'status_code' => 404,
                    'success' => false,
                    'message' => 'User tidak ditemukan',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'status_code' => 200,
                'success' => true,
                'message' => '',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 401,
                'success' => false,
                'message' => 'Token tidak valid atau telah expired',
                'data' => null,
            ], 401);
        }
    }

    /**
     * Refresh JWT token.
     */
    public function refresh(Request $request)
    {
        try {
            $token = $this->jwt->parseToken();
            $newToken = $token->refresh();

            return response()->json([
                'status_code' => 200,
                'success' => true,
                'message' => 'Token refreshed successfully',
                'data' => [
                    'access_token' => $newToken,
                    'token_type' => 'Bearer',
                    'expires_in' => $this->jwt->factory()->getTTL() * 60,
                ],
            ]);
        } catch (\Tymon\JwtAuth\Exceptions\TokenExpiredException $e) {
            return response()->json([
                'status_code' => 401,
                'success' => false,
                'message' => 'Token telah expired dan tidak bisa di-refresh',
                'data' => null,
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 401,
                'success' => false,
                'message' => 'Failed to refresh token',
                'data' => null,
            ], 401);
        }
    }

    /**
     * Logout user - invalidate token.
     */
    public function logout(Request $request)
    {
        try {
            $this->jwt->parseToken()->invalidate();

            return response()->json([
                'status_code' => 200,
                'success' => true,
                'message' => 'Logout berhasil',
                'data' => null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'message' => 'Failed to logout',
                'data' => null,
            ], 500);
        }
    }

    /**
     * Change password for authenticated user.
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = $this->jwt->parseToken()->authenticate();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status_code' => 401,
                    'success' => false,
                    'message' => 'Password saat ini tidak sesuai',
                    'data' => null,
                ], 401);
            }

            $user->update([
                'password' => bcrypt($request->new_password),
            ]);

            return response()->json([
                'status_code' => 200,
                'success' => true,
                'message' => 'Password berhasil diubah',
                'data' => null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'message' => 'Failed to change password',
                'data' => null,
            ], 500);
        }
    }
}
