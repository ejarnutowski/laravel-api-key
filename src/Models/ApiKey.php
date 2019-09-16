<?php

namespace Ejarnutowski\LaravelApiKey\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    use SoftDeletes;

    const EVENT_NAME_CREATED     = 'created';
    const EVENT_NAME_ACTIVATED   = 'activated';
    const EVENT_NAME_DEACTIVATED = 'deactivated';
    const EVENT_NAME_DELETED     = 'deleted';

    protected static $nameRegex = '/^[a-z0-9-]{1,255}$/';

    protected $table = 'api_keys';

    /**
     * Get the related ApiKeyAccessEvents records
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accessEvents()
    {
        return $this->hasMany(ApiKeyAccessEvent::class, 'api_key_id');
    }

    /**
     * Get the related ApiKeyAdminEvents records
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adminEvents()
    {
        return $this->hasMany(ApiKeyAdminEvent::class, 'api_key_id');
    }

    /**
     * Bootstrapping event handlers
     */
    public static function boot()
    {
        parent::boot();

        static::created(function(ApiKey $apiKey) {
            self::logApiKeyAdminEvent($apiKey, self::EVENT_NAME_CREATED);
        });

        static::updated(function($apiKey) {

            $changed = $apiKey->getDirty();

            if (isset($changed) && $changed['active'] === 1) {
                self::logApiKeyAdminEvent($apiKey, self::EVENT_NAME_ACTIVATED);
            }

            if (isset($changed) && $changed['active'] === 0) {
                self::logApiKeyAdminEvent($apiKey, self::EVENT_NAME_DEACTIVATED);
            }

        });

        static::deleted(function($apiKey) {
            self::logApiKeyAdminEvent($apiKey, self::EVENT_NAME_DELETED);
        });
    }

    /**
     * Generate a secure unique API key
     *
     * @return string
     */
    public static function generate()
    {
        do {
            $key = Str::random(64);
        } while (self::keyExists($key));

        return $key;
    }

    /**
     * Get ApiKey record by key value
     *
     * @param string $key
     * @return bool
     */
    public static function getByKey($key)
    {
        return self::where([
            'key'    => $key,
            'active' => 1
        ])->first();
    }

    /**
     * Check if key is valid
     *
     * @param string $key
     * @return bool
     */
    public static function isValidKey($key)
    {
        return self::getByKey($key) instanceof self;
    }

    /**
     * Check if name is valid format
     *
     * @param string $name
     * @return bool
     */
    public static function isValidName($name)
    {
        return (bool) preg_match(self::$nameRegex, $name);
    }

    /**
     * Check if a key already exists
     *
     * Includes soft deleted records
     *
     * @param string $key
     * @return bool
     */
    public static function keyExists($key)
    {
        return self::where('key', $key)->withTrashed()->first() instanceof self;
    }

    /**
     * Check if a name already exists
     *
     * Does not include soft deleted records
     *
     * @param string $name
     * @return bool
     */
    public static function nameExists($name)
    {
        return self::where('name', $name)->first() instanceof self;
    }

    /**
     * Log an API key admin event
     *
     * @param ApiKey $apiKey
     * @param string $eventName
     */
    protected static function logApiKeyAdminEvent(ApiKey $apiKey, $eventName)
    {
        $event             = new ApiKeyAdminEvent;
        $event->api_key_id = $apiKey->id;
        $event->ip_address = request()->ip();
        $event->event      = $eventName;
        $event->save();
    }
}
