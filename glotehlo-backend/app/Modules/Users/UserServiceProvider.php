<?php


namespace App\Modules\Users;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');


        Route::middleware('api')      //so this one deals automatically with authentication, routing and late limiting
            ->prefix('api')
            ->group(__DIR__ . '/routes/api.php');      // i like it
        
    }
}

