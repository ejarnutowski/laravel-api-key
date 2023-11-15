<?php

namespace Cable8mm\LaravelApiKey\Http\Middleware;

use Cable8mm\LaravelApiKey\Exceptions\LaravelApiKeyException;
use Cable8mm\LaravelApiKey\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;

class AuthorizeApiKey
{
    const AUTH_HEADER = 'X-Authorization';

    /**
     * Handle the incoming request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header(self::AUTH_HEADER);
        $apiKey = ApiKey::getByKey($header);

        if ($apiKey instanceof ApiKey) {
            return $next($request);
        }

        throw new LaravelApiKeyException('Invalid ApiKey instance from request in middleware');
    }
}
