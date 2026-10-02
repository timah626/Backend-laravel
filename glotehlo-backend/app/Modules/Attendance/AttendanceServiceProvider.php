<?php

namespace App\Modules\Attendance;


use Illuminate\Support\ServiceProvider;

class AttendanceServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

    }
}