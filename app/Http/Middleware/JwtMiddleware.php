<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JwtAuth\Exceptions\JwtException;
use Tymon\JwtAuth\Exceptions\TokenExpiredException;
use Tymon\JwtAuth\Facades\JwtAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JwtAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token telah expired',
                'error' => 'token_expired',
            ], 401);
        } catch (JwtException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid',
                'error' => 'invalid_token',
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Authorization Failed',
                'error' => $e->getMessage(),
            ], 401);
        }

        return $next($request);
    }
}
