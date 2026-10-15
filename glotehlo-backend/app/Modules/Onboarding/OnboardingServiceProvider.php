<?php

namespace App\Modules\Onboarding;


use Illuminate\Support\Facades\Route;



use Illuminate\Support\ServiceProvider;
use App\Modules\Onboarding\Console\Commands\RemoveRejectedApplications;

class OnboardingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');


        Route::middleware('api')
            ->prefix('api')
            ->group(__DIR__ . '/routes/api.php');

        $this->commands([RemoveRejectedApplications::class]);









    }
}
