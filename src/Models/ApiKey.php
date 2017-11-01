<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiKey extends Model
{
    use SoftDeletes;

    protected static $keyRegex = '/^[a-zA-Z0-9]{64}$/';
    protected        $table    = 'api_keys';

    /**
     * Validate format of key
     *
     * @param string $key
     * @return bool
     */
    public static function validateFormat($key)
    {
        return !!preg_match(self::$keyRegex, $key);
    }
}
