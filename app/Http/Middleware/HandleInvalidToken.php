<?php

namespace App\Http\Middleware;

use Closure;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class HandleInvalidToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Token has expired',
            ], 403);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Token is invalid',
            ], 403);
        } catch (JWTException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Token is not provided',
            ], 403);
        }

        return $next($request);
    }
}
