<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if (
                $e instanceof
                \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException
            ) {
                return $this->sendErrorToken("Token is Invalid");
            } elseif (
                $e instanceof
                \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException
            ) {
                try {
                    if ($request->is('api/refresh')) {
                        return $next($request);
                    } else {
                        $user = JWTAuth::parseToken()->authenticate();
                    }
                } catch (Exception $e) {
                    return $this->sendExpiredToken('Token is Expired');
                }
            } else {
                return $this->sendErrorToken("Authorization Token not found");
            }
        }
        return $next($request);
    }

    protected function sendErrorToken($message)
    {
        return response()->json(
            [
                "success" => false,
                "message" => $message,
            ],
            Response::HTTP_BAD_REQUEST
        );
    }

    protected function sendExpiredToken($message)
    {
        return response()->json(
            [
                "success" => false,
                "refresh" => true,
                "message" => $message,
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }
}
