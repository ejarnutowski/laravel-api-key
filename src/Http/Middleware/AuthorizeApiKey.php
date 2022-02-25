<?php

namespace Ejarnutowski\LaravelApiKey\Http\Middleware;

use Closure;
use Ejarnutowski\LaravelApiKey\Models\ApiKey;
use Ejarnutowski\LaravelApiKey\Models\ApiKeyAccessEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $parts = explode('.', $header);
        $apiKey = ApiKey::getByKey($parts[0]);
        error_log($apiKey instanceof ApiKey);
        error_log($apiKey->key);
        error_log($this->matchingHash($parts[1], $apiKey->key));

        if ($apiKey instanceof ApiKey && $this->matchingHash($parts[1], $apiKey->key)) {
            $this->logAccessEvent($request, $apiKey);
            return $next($request);
        }

        return response([
            'errors' => [[
                'message' => 'Unauthorized'
            ]]
        ], 401);
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

    private function matchingHash($headerKey, $databaseKey)
    {
        $hashedSuffix = substr($databaseKey, 7);
        return Hash::check($headerKey, $hashedSuffix);
    }
}
