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

        $keys->each(function($key) {

            $status = $key->active    ? 'active'  : 'inactive';
            $status = $key->trashed() ? 'deleted' : $status;

            $this->info($key->name . ' : ' . $key->key . ' : ' . $status);
        });
    }
}
