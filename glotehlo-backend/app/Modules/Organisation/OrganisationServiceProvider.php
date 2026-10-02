<?php

namespace App\Modules\Organisation;


use Illuminate\Support\ServiceProvider;

class OrganisationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

    }
}
