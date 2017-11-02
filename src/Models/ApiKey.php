<?php

namespace Ejarnutowski\LaravelApiKey\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiKey extends Model
{
    use SoftDeletes;

    protected static $nameRegex = '/^[a-z-]{1,255}$/';
    protected        $table     = 'api_keys';

    /**
     * Get the related ApiKeyAccessEvents records
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accessEvents()
    {
        return $this->hasMany('Ejarnutowski\LaravelApiKey\Models\ApiKeyAccessEvents', 'api_key_id');
    }

    /**
     * Generate a secure unique API key
     *
     * @return string
     */
    public static function generate()
    {
        do {
            $key = str_random(64);
        } while (self::keyExists($key));

        return $key;
    }

    /**
     * Check if key is valid
     *
     * @param string $key
     * @return bool
     */
    public static function isValidKey($key)
    {
        return self::where([
            'key'    => $key,
            'active' => 1
        ])->first() instanceof self;
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
     * Check if a name already exists
     *
     * @param string $name
     * @return bool
     */
    public static function nameExists($name)
    {
        return self::where('name', $name)->withTrashed()->first() instanceof self;
    }

    /**
     * Check if a key already exists
     *
     * @param string $key
     * @return bool
     */
    private static function keyExists($key)
    {
        return self::where('key', $key)->withTrashed()->first() instanceof self;
    }
}
