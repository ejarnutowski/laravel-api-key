<?php

namespace Ejarnutowski\LaravelApiKey\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKeyAccessEvent extends Model
{
    protected $table = 'api_key_access_events';

    /**
     * Get the related ApiKey record
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apiKey()
    {
        return $this->belongsTo('Ejarnutowski\LaravelApiKey\Models\ApiKey', 'api_key_id');
    }

}
