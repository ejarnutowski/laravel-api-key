<?php

namespace Ejarnutowski\LaravelApiKey\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKeyAccessEvent extends Model
{
    protected $table = 'api_key_access_events';

    protected $fillable = [
        'ip_address',
        'url',
    ];

    /**
     * Get the related ApiKey record
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apiKey()
    {
        return $this->belongsTo(ApiKey::class, 'api_key_id');
    }

}
