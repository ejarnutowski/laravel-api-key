<?php

namespace Ejarnutowski\LaravelApiKey\Http\Middleware;

use Closure;
use Ejarnutowski\LaravelApiKey\Models\ApiKey;
use Illuminate\Http\Request;

class AuthorizeApiKey
{
    const AUTH_HEADER = 'X-Authorization';

    /**
     * Handle the incoming request
     *
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header(self::AUTH_HEADER);

        if (ApiKey::isValidKey($key)) {
            return $next($request);
        }

        return response([
            'errors' => [[
                'message' => 'Unauthorized'
            ]]
        ], 401);
    }

}
