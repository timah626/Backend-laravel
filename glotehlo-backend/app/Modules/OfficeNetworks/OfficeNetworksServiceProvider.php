<?php


namespace App\Modules\OfficeNetworks;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class OfficeNetworksServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

    }
}
