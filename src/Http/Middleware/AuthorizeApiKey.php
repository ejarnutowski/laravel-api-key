<?php

namespace Ejarnutowski\LaravelApiKey\Http\Middleware;

use Closure;
use Ejarnutowski\LaravelApiKey\Models\ApiKey;
use Ejarnutowski\LaravelApiKey\Models\ApiKeyAccessEvent;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
        $header = $request->header(self::AUTH_HEADER);
        $apiKey = ApiKey::getByKey($header);

        if ($apiKey instanceof ApiKey) {
            $this->logAccessEvent($request, $apiKey);
            return $next($request);
        }

        throw new UnauthorizedHttpException(self::AUTH_HEADER);
    }

    /**
     * Log an API key access event
     *
     * @param Request $request
     * @param ApiKey  $apiKey
     */
    protected function logAccessEvent(Request $request, ApiKey $apiKey)
    {
        $event = new ApiKeyAccessEvent;
        $event->api_key_id = $apiKey->id;
        $event->ip_address = $request->ip();
        $event->url        = $request->fullUrl();
        $event->save();
    }
}
