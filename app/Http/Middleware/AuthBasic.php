<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\ApiResponse;

class AuthBasic
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
        if (($request->getUser() != config('constants.api_key.api_username')) || ($request->getPassword() != config('constants.api_key.api_password'))) {
            return ApiResponse::responseWarning("Invalid Credential");
        } else {

            return $next($request);
        }
    }
}
