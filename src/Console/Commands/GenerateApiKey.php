<?php

namespace Ejarnutowski\LaravelApiKey\Console\Commands;

use Ejarnutowski\LaravelApiKey\Models\ApiKey;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class GenerateApiKey extends Command
{
    /**
     * Error messages
     */
    const MESSAGE_ERROR_INVALID_NAME_FORMAT = 'Invalid name.  Must be a lowercase alphabetic characters, numbers and hyphens less than 255 characters long.';
    const MESSAGE_ERROR_NAME_ALREADY_USED   = 'Name is unavailable.';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apikey:generate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new API key';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $error = $this->validateName($name);

        if ($error) {
            $this->error($error);
            return;
        }

        $apiKey       = new ApiKey;
        $apiKey->name = $name;
        $generatedKey = ApiKey::generate();
        $apiKey->key  = $generatedKey['hashed'];
        $apiKey->save();

        $this->info('API key created. Be sure to save this somewhere safe as you will not be able to see it again');
        $this->info('Name: ' . $apiKey->name);
        $this->info('Key: '  . $generatedKey['plain']);
    }

    /**
     * Validate name
     *
     * @param string $name
     * @return string
     */
    protected function validateName($name)
    {
        if (!ApiKey::isValidName($name)) {
            return self::MESSAGE_ERROR_INVALID_NAME_FORMAT;
        }
        if (ApiKey::nameExists($name)) {
            return self::MESSAGE_ERROR_NAME_ALREADY_USED;
        }
        return null;
    }
}
