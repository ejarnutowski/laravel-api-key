<?php

namespace Ejarnutowski\LaravelApiKey\Http\Middleware;

use Closure;
use Ejarnutowski\LaravelApiKey\Models\ApiKey;
use Ejarnutowski\LaravelApiKey\Models\ApiKeyAccessEvent;
use Illuminate\Http\Request;
use App\Models\Domain;

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

        if ($apiKey instanceof ApiKey && $this->checkAcess($request, $apiKey)) {
            $this->updaterequestNumber($request, $apiKey);
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

    protected function checkAcess(Request $request, ApiKey $apiKey)
    {
        $info = array('ip_request' => $request->ip(),'id_key' => $apiKey->id, 'host' => $request->getSchemeAndHttpHost());
        return (bool) Domain::infoByid($info);
    }

    protected function updaterequestNumber(Request $request, ApiKey $apiKey)
    {
        $info = array('ip_request' => $request->ip(),'id_key' => $apiKey->id, 'host' => $request->getSchemeAndHttpHost());
        $res  = Domain::decreaseResquest($info);
    }
}
