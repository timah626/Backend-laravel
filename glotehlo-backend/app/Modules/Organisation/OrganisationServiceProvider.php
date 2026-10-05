<?php

namespace App\Modules\Organisation;





use Illuminate\Support\ServiceProvider;


use Illuminate\Support\Facades\Route;



class OrganisationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');


        Route::middleware('api')
            ->prefix('api')
            ->group(__DIR__ . '/routes/api.php');


    }
}
