<?php

namespace Ejarnutowski\LaravelApiKey\Console\Commands;

use Ejarnutowski\LaravelApiKey\Models\ApiKey;
use Illuminate\Console\Command;

class ListApiKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apikey:list {--D|deleted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all API Keys';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keys = $this->option('deleted')
            ? ApiKey::withTrashed()->orderBy('name')->get()
            : ApiKey::orderBy('name')->get();

        if ($keys->count() === 0) {
            $this->info('There are no API keys');
            return;
        }

        $headers = ['Name', 'ID', 'Status', 'Status Date', 'Key'];

        $rows = $keys->map(function($key) {

            $status = $key->active    ? 'active'  : 'deactivated';
            $status = $key->trashed() ? 'deleted' : $status;

            $statusDate = $key->deleted_at ?: $key->updated_at;

            return [
                $key->name,
                $key->id,
                $status,
                $statusDate,
                $key->key
            ];

        });

        $this->table($headers, $rows);
    }
}
