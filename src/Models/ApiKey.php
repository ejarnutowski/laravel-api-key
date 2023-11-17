<?php

namespace Cable8mm\LaravelApiKey\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    use SoftDeletes;

    const EVENT_NAME_CREATED = 'created';

    const EVENT_NAME_ACTIVATED = 'activated';

    const EVENT_NAME_DEACTIVATED = 'deactivated';

    const EVENT_NAME_DELETED = 'deleted';

    protected static $nameRegex = '/^[a-z0-9-]{1,255}$/';

    protected $table = 'api_keys';

    /**
     * Generate a secure unique API key
     */
    public static function generate(): string
    {
        do {
            $key = Str::random(64);
        } while (self::keyExists($key));

        return $key;
    }

    /**
     * Get ApiKey record by key value
     *
     * @param  string  $key
     */
    public static function getByKey($key)
    {
        return self::where([
            'key' => $key,
            'active' => 1,
        ])->first();
    }

    /**
     * Check if key is valid
     *
     * @param  string  $key
     */
    public static function isValidKey($key): bool
    {
        return self::getByKey($key) instanceof self;
    }

    /**
     * Check if name is valid format
     *
     * @param  string  $name
     */
    public static function isValidName($name): bool
    {
        return (bool) preg_match(self::$nameRegex, $name);
    }

    /**
     * Check if a key already exists
     *
     * Includes soft deleted records
     *
     * @param  string  $key
     */
    public static function keyExists($key): bool
    {
        return self::where('key', $key)->withTrashed()->first() instanceof self;
    }

    /**
     * Check if a name already exists
     *
     * Does not include soft deleted records
     *
     * @param  string  $name
     */
    public static function nameExists($name): bool
    {
        return self::where('name', $name)->first() instanceof self;
    }
}
