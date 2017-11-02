<?php

namespace Ejarnutowski\LaravelApiKey\Console\Commands;

use Ejarnutowski\LaravelApiKey\Models\ApiKey;
use Illuminate\Console\Command;

class GenerateApiKey extends Command
{
    /**
     * Error messages
     */
    const MESSAGE_ERROR_INVALID_NAME_FORMAT = 'Invalid name.  Name must be a lowercase alphabetic string less than 255 characters.';
    const MESSAGE_ERROR_NAME_UNAVAILABLE    = 'Name is already used.';

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
        $apiKey->key  = ApiKey::generate();
        $apiKey->save();

        $this->info('API KEY CREATED');
        $this->info('Name: ' . $apiKey->name);
        $this->info('Key: '  . $apiKey->key);
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
            return self::MESSAGE_ERROR_NAME_UNAVAILABLE;
        }
        return null;
    }
}
