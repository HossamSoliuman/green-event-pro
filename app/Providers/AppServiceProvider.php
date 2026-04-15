<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Services\CarbonFootprintService::class);
        $this->app->singleton(\App\Services\UZ62ScoringService::class);
    }
    public function boot(): void {}
}
