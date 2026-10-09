<?php

namespace App\Modules\Attendance;
use Illuminate\Support\Facades\Route;




use Illuminate\Support\ServiceProvider;

class AttendanceServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');



        Route::middleware('api')
            ->prefix('api')
            ->group(__DIR__ . '/routes/api.php');




    }
}
