<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Add error handling to see what's causing the issue
        if (env('APP_DEBUG')) {
            \Event::listen('Illuminate\Foundation\Exceptions\ReportableHandler', function ($exception) {
                \Log::error('Exception: ' . $exception->getMessage());
                \Log::error('File: ' . $exception->getFile());
                \Log::error('Line: ' . $exception->getLine());
                \Log::error('Trace: ' . $exception->getTraceAsString());
            });
        }
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}