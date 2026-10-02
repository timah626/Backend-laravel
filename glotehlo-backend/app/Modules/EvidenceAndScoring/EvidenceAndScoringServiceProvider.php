<?php

namespace App\Modules\EvidenceAndScoring;


use Illuminate\Support\ServiceProvider;

class EvidenceAndScoringServiceProvider  extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

    }
}
